<?php

namespace App\Livewire\Admin\Companies;

use App\Models\CoCType;
use App\Models\CompanyItem;
use App\Models\Plot;
use App\Models\Player;
use App\Models\Company;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class EditCompany extends Component
{
    use WithPagination;

    public $company;
    public $companyName;
    public $cocNumber;
    public $cocType;
    public $worldId;
    public $selectedPlayer;

    public $currency;

    public $players;
    public $cocTypes;

    public $searchEmployees = '';
    public $searchBankAccounts = '';
    public $searchPlots = '';
    public $searchPinConsoles = '';
    public $searchItems = '';

    public $employeesPerPage = 5;
    public $accountsPerPage = 5;
    public $plotsPerPage = 5;
    public $pinConsolesPerPage = 5;
    public $itemsPerPage = 10;

    public $employeeToRemove = null;
    public $bankAccountToRemove = null;
    public $plotToRemove = null;
    public $pinConsoleToRemove = null;
    public $itemToRemove = null;

    public $removeEmployeeModal = false;
    public $removeBankAccountModal = false;
    public $removePlotModal = false;
    public $removePinConsoleModal = false;
    public $removeItemModal = false;

    public $assignEmployeeModal = false;
    public $assignPlotModal = false;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
        $this->players = Player::orderBy('username')->get(['uuid', 'username']);
        $this->cocTypes = CoCType::get(['id', 'name']);

        $this->companyName = $this->company->name;
        $this->cocNumber = $this->company->coc_number;
        $this->cocType = $this->company->coc_type;
        $this->worldId = $this->company->world_id;
        $this->selectedPlayer = $this->company->owner_uuid;

        $this->currency = $this->company->bankAccounts()->where('is_main', true)->first()?->currency ?? 'EUR';
    }

    // Search queries
    public function updated($key, $value) {
        if ($key === 'searchEmployees') {
            $this->resetPage('employees');
        }

        if ($key === 'searchBankAccounts') {
            $this->resetPage('accountsPerPage');
        }

        if ($key === 'searchPlots') {
            $this->resetPage('plotsPerPage');
        }

        if ($key === 'searchPinConsoles') {
            $this->resetPage('pinConsolesPerPage');
        }

        if ($key === 'searchItems') {
            $this->resetPage('itemsPerPage');
        }
    }

    public function updateCompany(ApiService $apiService) {
        // Store the current data for rollback in case of failure
        $originalData = [
            'name' => $this->company->name,
            'coc_number' => $this->company->coc_number,
            'world_id' => $this->company->world_id,
            'owner_uuid' => $this->company->owner_uuid,
        ];

        // 1. Validate input
        $data = $this->validate([
            'companyName'    => ['required', 'string', 'max:255'],
            'cocNumber'      => ['required', 'string', 'max:20'],
            'worldId'        => ['required', 'string', 'max:255'],
            'selectedPlayer' => ['nullable', 'string', 'exists:players,uuid'],
        ]);

        // 2. Optimistic update
        $this->company->name = $this->companyName;
        $this->company->coc_number = $this->cocNumber;
        $this->company->world_id = $this->worldId;
        $this->company->owner_uuid = $this->selectedPlayer;
        $this->company->save();

        // 3. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$this->company->id}");

        // Immediate failure (request not accepted)
        if ($status !== 202) {
            $this->rollbackCompany($originalData);
            Toaster::error(__('admin.toast.companies.update_failed'));
            return;
        }

        // 3. Poll for result
        if (!$success) {
            $this->rollbackCompany($originalData);
            Toaster::error(__('admin.toast.companies.update_failed'));
            return;
        }

        // 4. Success
        Toaster::success(__('admin.toast.companies.updated'));
    }

    // Delete Employee
    public function removeEmployee($uuid) {
        $this->employeeToRemove = $this->company->employees()->where('player_uuid', $uuid)->first();
        $this->removeEmployeeModal = true;
    }

    public function destroyEmployee(ApiService $apiService) {
        if (!$this->employeeToRemove) return;

        // Store the current employee for rollback in case of failure
        $employee = $this->employeeToRemove;

        // 1. Optimistic delete
        $this->employeeToRemove->delete();
        $this->removeEmployeeModal = false;
        $this->employeeToRemove = null;

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$this->company->id}");

        // Immediate failure (did not accept request)
        if ($status !== 202) {
            $this->rollbackEmployee($employee);
            Toaster::error(__('admin.toast.companies.employee_remove_failed'));
            return;
        }

        // 3. Poll for result
        if (!$success) {
            $this->rollbackEmployee($employee);
            Toaster::error(__('admin.toast.companies.employee_remove_failed'));
            return;
        }

        // 4. Success
        Toaster::success(__('admin.toast.companies.employee_removed'));
    }

    // Delete Bank Account
    public function removeBankAccount($id) {
        $this->bankAccountToRemove = $this->company->bankAccounts()->where('id', $id)->first();
        $this->removeBankAccountModal = true;
    }

    public function destroyBankAccount(ApiService $apiService) {
        if (!$this->bankAccountToRemove) return;

        // Store the current bank account for rollback in case of failure
        $bankAccount = $this->bankAccountToRemove;

        // 1. Optimistic delete
        $this->bankAccountToRemove->delete();
        $this->removeBankAccountModal = false;
        $this->bankAccountToRemove = null;

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$this->company->id}");

        // Immediate failure (did not accept request)
        if ($status !== 202) {
            $this->company->bankAccounts()->save($bankAccount);
            return Toaster::error(__('admin.toast.companies.bank_account_remove_failed'));
        }
        
        // 3. Poll for result
        if (!$success) {
            $this->company->bankAccounts()->save($bankAccount);
            return Toaster::error(__('admin.toast.companies.bank_account_remove_failed'));
        }

        // 4. Success
        Toaster::success(__('admin.toast.companies.bank_account_removed'));
    }

    // Delete Plot (relation)
    public function removePlot($id) {
        $this->plotToRemove = $this->company->plots()->where('id', $id)->first();
        $this->removePlotModal = true;
    }

    public function unlinkPlot(ApiService $apiService) {
        if (!$this->plotToRemove) return;

        // Store the current plot for rollback in case of failure
        $plot = $this->plotToRemove;

        $companyId = $plot->company_id;
        $plotId = $plot->plot_id;

        // 1. Optimistic unlink
        $plot->company_id = null;
        $plot->save();

        $this->removePlotModal = false;
        $this->plotToRemove = null;

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/plot/{$plotId}");

        // Immediate failure (did not accept request)
        if ($status !== 202) {
            $this->rollbackPlot($plot, $companyId);
            Toaster::error(__('admin.toast.companies.plot_remove_failed'));
            return;
        }

        // 3. Poll for result (short, bounded wait)
        if (!$success) {
            $this->rollbackPlot($plot, $companyId);
            Toaster::error(__('admin.toast.companies.plot_remove_failed'));
            return;
        }

        // 4. Success
        Toaster::success(__('admin.toast.companies.plot_removed'));
    }

    // Delete PIN Console (relation)
    public function removePinConsole($id) {
        $this->pinConsoleToRemove = $this->company->pinConsoles()->where('id', $id)->first();
        $this->removePinConsoleModal = true;
    }

    public function destroyPinConsole(ApiService $apiService) {
        if (!$this->pinConsoleToRemove) return;

        // Store the current pin console for rollback in case of failure
        $pinConsole = $this->pinConsoleToRemove;

        // 1. Optimistic delete
        $this->pinConsoleToRemove->delete();
        $this->removePinConsoleModal = false;
        $this->pinConsoleToRemove = null;

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/pin-console/{$pinConsole->id}");

        // Immediate failure (did not accept request)
        if ($status !== 202) {
            $this->rollbackPinConsole($pinConsole);
            return Toaster::error(__('admin.toast.companies.pin_console_remove_failed'));
        }
        
        // 3. Poll for result
        if (!$success) {
            $this->rollbackPinConsole($pinConsole);
            return Toaster::error(__('admin.toast.companies.pin_console_remove_failed'));
        }

        // 4. Success
        Toaster::success(__('admin.toast.companies.pin_console_removed'));
    }

    // Delete Item
    public function removeItem($id) {
        $this->itemToRemove = CompanyItem::where('id', $id)->with('item')->first();
        $this->removeItemModal = true;
    }

    public function destroyItem() {
        $this->itemToRemove->delete();

        $this->removeItemModal = false;
        Toaster::success(__('admin.toast.companies.item_removed'));
    }

    public function render()
    {
        return view('livewire.admin.companies.edit-company', [
            'employees' => $this->company
                ->employees()
                ->when($this->searchEmployees !== '', function ($q) {
                    $q->whereHas('player', function ($q) {
                        $q->where('username', 'like', '%' . $this->searchEmployees . '%');
                    });
                })
                ->orderByRaw("
                    CASE role
                        WHEN 'manager' THEN 1
                        WHEN 'employee' THEN 2
                        ELSE 3
                    END
                ")
                ->paginate($this->employeesPerPage, ['*'], 'employees'),
            'bankAccounts' => $this->company
                ->bankAccounts()
                ->when($this->searchBankAccounts !== '', function ($q) {
                    $q->where('id', 'like', '%' . $this->searchBankAccounts . '%');
                })
                ->paginate($this->accountsPerPage, ['*'], 'accounts'),
            'plots' => $this->company
                ->plots()
                ->when($this->searchPlots !== '', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchPlots . '%');
                    $q->orWhere('plot_id', 'like', '%' . $this->searchPlots . '%');
                })
                ->paginate($this->plotsPerPage, ['*'], 'plots'),
            'pinConsoles' => $this->company
                ->pinConsoles()
                ->when($this->searchPinConsoles !== '', function ($q) {
                    $q->where('id', 'like', '%' . $this->searchPinConsoles . '%');
                    $q->orWhere('account_id', 'like', '%' . $this->searchPinConsoles . '%');
                })
                ->paginate($this->pinConsolesPerPage, ['*'], 'pinConsoles'),
            'items' => $this->company
                ->items()
                ->when($this->searchItems !== '', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchItems . '%');
                    $q->orWhere('item_id', 'like', '%' . $this->searchItems . '%');
                })
                ->paginate($this->itemsPerPage, ['*'], 'items'),
        ]);
    }

    private function rollbackCompany(array $originalData): void {
        $this->company->name = $originalData['name'];
        $this->company->coc_number = $originalData['coc_number'];
        $this->company->world_id = $originalData['world_id'];
        $this->company->owner_uuid = $originalData['owner_uuid'];
        $this->company->save();
    }

    private function rollbackEmployee($employee): void {
        $employee->save();
    }

    private function rollbackPlot(Plot $plot, int $companyId): void {
        $plot->company_id = $companyId;
        $plot->save();
    }

    private function rollbackPinConsole($pinConsole): void {
        $pinConsole->save();
    }

}
