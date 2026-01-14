<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Player;
use Masmerise\Toaster\Toaster;
use Livewire\Component;
use Livewire\WithPagination;

class EditCompany extends Component
{
    use WithPagination;

    public $company;
    public $companyName;
    public $cocNumber;
    public $worldId;
    public $selectedPlayer;

    public $players;

    public $searchEmployees = '';
    public $searchBankAccounts = '';
    public $searchPlots = '';
    public $searchPinConsoles = '';

    public $employeesPerPage = 5;
    public $accountsPerPage = 5;
    public $plotsPerPage = 5;
    public $pinConsolesPerPage = 5;

    public $employeeToRemove = null;
    public $bankAccountToRemove = null;
    public $plotToRemove = null;
    public $pinConsoleToRemove = null;

    public $removeEmployeeModal = false;
    public $removeBankAccountModal = false;
    public $removePlotModal = false;
    public $removePinConsoleModal = false;

    public $assignEmployeeModal = false;
    public $assignPlotModal = false;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
        $this->players = Player::orderBy('username')->get();

        $this->companyName = $this->company->name;
        $this->cocNumber = $this->company->coc_number;
        $this->worldId = $this->company->world_id;
        $this->selectedPlayer = $this->company->owner_uuid;
    }

    // Search queries
    public function updatedSearchEmployees() {
        $this->resetPage('employees');
    }

    public function updatedSearchBankAccounts() {
        $this->resetPage('accountsPerPage');
    }

    public function updatedSearchPlots() {
        $this->resetPage('plotsPerPage');
    }

    public function updatedSearchPinConsoles() {
        $this->resetPage('pinConsolesPerPage');
    }

    // Update Company
    public function updateCompany() {
        $data = $this->validate([
            'companyName'    => ['required', 'string', 'max:255'],
            'cocNumber'      => ['required', 'string', 'max:20'],
            'worldId'        => ['required', 'string', 'max:255'],
            'selectedPlayer' => ['nullable', 'string', 'exists:players,uuid'],
        ]);

        $this->company->name = $data['companyName'];
        $this->company->world_id = $data['worldId'];
        $this->company->coc_number = $data['cocNumber'];
        $this->company->owner_uuid = $data['selectedPlayer'];
        $this->company->save();

        return redirect()->route('admin.companies.render')->success(__('admin.toast.company.updated'));
    }

    // Delete Employee
    public function removeEmployee($uuid) {
        $this->employeeToRemove = $this->company->employees()->where('player_uuid', $uuid)->first();
        $this->removeEmployeeModal = true;
    }

    public function destroyEmployee() {
        if (!$this->employeeToRemove) return;

        $this->employeeToRemove->delete();
        $this->removeEmployeeModal = false;
        $this->employeeToRemove = null;

        Toaster::success(__('admin.toast.company.employee_removed'));
    }

    // Delete Bank Account
    public function removeBankAccount($id) {
        $this->bankAccountToRemove = $this->company->bankAccounts()->where('id', $id)->first();
        $this->removeBankAccountModal = true;
    }

    public function destroyBankAccount() {
        if (!$this->bankAccountToRemove) return;

        $this->bankAccountToRemove->delete();
        $this->removeBankAccountModal = false;
        $this->bankAccountToRemove = null;

        Toaster::success(__('admin.toast.company.bank_account_removed'));
    }

    // Delete Plot (relation)
    public function removePlot($id) {
        $this->plotToRemove = $this->company->plots()->where('id', $id)->first();
        $this->removePlotModal = true;
    }

    public function destroyPlot() {
        if (!$this->plotToRemove) return;

        $this->plotToRemove->delete();
        $this->removePlotModal = false;
        $this->plotToRemove = null;

        Toaster::success(__('admin.toast.company_plot_removed'));
    }

    // Delete Pin Console (relation)
    public function removePinConsole($id) {
        $this->pinConsoleToRemove = $this->company->pinConsoles()->where('id', $id)->first();
        $this->removePinConsoleModal = true;
    }

    public function destroyPinConsole() {
        if (!$this->pinConsoleToRemove) return;

        $this->pinConsoleToRemove->delete();
        $this->removePinConsoleModal = false;
        $this->pinConsoleToRemove = null;

        Toaster::success(__('admin.toast.company_pin_console_removed'));
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
        ]);
    }
}
