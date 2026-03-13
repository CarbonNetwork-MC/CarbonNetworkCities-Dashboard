<?php

namespace App\Livewire\Admin\BankAccounts;

use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Models\CompanyBankaccount;
use App\Models\PersonalBankaccount;
use App\Services\RedisService;

class Overview extends Component
{
    use WithPagination;

    public $companySearch = '';
    public $personalSearch = '';

    public $companyBankAccountsPerPage = 5;
    public $personalBankAccountsPerPage = 10;

    public $selectedCompanyBankAccount = null;
    public $selectedPersonalBankAccount = null;

    public $deleteCompanyBankAccountModal = false;
    public $deletePersonalBankAccountModal = false;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'companySearch') {
            $this->resetPage('companyBankAccountsPage');
        }

        if ($key === 'personalSearch') {
            $this->resetPage('personalBankAccountsPage');
        }
    }

    // ? Company Bankaccount Methods
    public function removeCompanyBankAccount($id) {
        $this->selectedCompanyBankAccount = CompanyBankaccount::find($id);
        $this->deleteCompanyBankAccountModal = true;
    }

    public function destroyCompanyBankAccount(RedisService $redisService) {
        if (!$this->selectedCompanyBankAccount) return;

        $selectedCompanyBankAccount = $this->selectedCompanyBankAccount;

        $this->selectedCompanyBankAccount->delete();

        $success = $redisService->invalidate('INVALIDATE_COMPANY', (string) $selectedCompanyBankAccount->company_id);
        if (!$success) {
            CompanyBankaccount::create($selectedCompanyBankAccount->toArray());
            Toaster::error(__('admin.toast.bank_accounts.company.invalidate_bankaccount_api_error'));
            return;
        }

        $this->reset([
            'selectedCompanyBankAccount',
            'deleteCompanyBankAccountModal',
        ]);

        Toaster::success(__('admin.toasts.bank_accounts.company.deleted'));
    }

    // ? Personal Bankaccount Methods
    public function removePersonalBankAccount($id) {
        $this->selectedPersonalBankAccount = PersonalBankaccount::find($id);
        $this->deletePersonalBankAccountModal = true;
    }

    public function destroyPersonalBankAccount(RedisService $redisService) {
        if (!$this->selectedPersonalBankAccount) return;

        $selectedPersonalBankAccount = $this->selectedPersonalBankAccount;

        $this->selectedPersonalBankAccount->delete();

        $success = $redisService->invalidate('INVALIDATE_PLAYER', (string) $selectedPersonalBankAccount->player_uuid);
        if (!$success) {
            PersonalBankaccount::create($selectedPersonalBankAccount->toArray());
            Toaster::error(__('admin.toast.bank_accounts.personal.invalidate_bankaccount_api_error'));
            return;
        }

        $this->reset([
            'selectedPersonalBankAccount',
            'deletePersonalBankAccountModal',
        ]);

        Toaster::success(__('admin.toasts.bank_accounts.personal.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts.overview', [
            'companyBankAccounts' => CompanyBankaccount::with('company')
                ->whereHas('company', function ($q) {
                    $q->where('name', 'like', '%' . $this->companySearch . '%')
                    ->orWhere('coc_number', 'like', '%' . $this->companySearch . '%');
                })
                ->orWhere('currency', 'like', '%' . strtoupper($this->companySearch) . '%')
                ->orderBy('company_id')
                ->orderBy('balance', 'desc')
                ->paginate($this->companyBankAccountsPerPage, ['*'], 'companyBankAccountsPage'),
            'personalBankAccounts' => PersonalBankaccount::with('player')
                ->whereHas('player', function ($q) {
                    $q->where('uuid', 'like', '%' . $this->personalSearch . '%')
                    ->orWhere('username', 'like', '%' . $this->personalSearch . '%');
                })
                ->orWhere('type', 'like', '%' . $this->personalSearch . '%')
                ->orWhere('currency', 'like', '%' . strtoupper($this->personalSearch) . '%')
                ->orderBy('player_uuid')
                ->orderBy('balance', 'desc')
                ->paginate($this->personalBankAccountsPerPage, ['*'], 'personalBankAccountsPage'),
        ]);
    }
}
