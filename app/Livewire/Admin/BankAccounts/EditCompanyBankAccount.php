<?php

namespace App\Livewire\Admin\BankAccounts;

use App\Models\Company;
use App\Models\Country;
use Livewire\Component;
use App\Models\CompanyBankaccount;
use App\Services\RedisService;

class EditCompanyBankAccount extends Component
{
    public $account;

    public $companies;
    public $countries;

    public $companyId;
    public $balance;
    public $isMain;
    public $currency;

    public function mount($id) {
        $this->account = CompanyBankaccount::findOrFail($id);

        $this->companyId = $this->account->company_id;
        $this->balance = $this->account->balance;
        $this->isMain = $this->account->is_main;
        $this->currency = $this->account->currency;

        $this->companies = Company::get(['id', 'name']);
        $this->countries = Country::get(['name', 'currency']);
    }

    public function updateCompanyBankAccount(RedisService $redisService) {
        if (!$this->account) return;

        $originalData = $this->account->toArray();

        $currencies = Country::pluck('currency')->toArray();

        $data = $this->validate([
            'companyId' => ['required', 'integer'],
            'balance' => ['required', 'numeric'],
            'isMain' => ['boolean'],
            'currency' => ['required', 'string', 'in:' . implode(',', $currencies)],
        ]);

        $company = Company::find($data['companyId']);
        $mainAccount = $company->bankAccounts->where('is_main', true)->first();

        if ($data['isMain'] == true && $mainAccount) {
            $mainAccount->is_main = false;
            $mainAccount->save();
        }

        $this->account->company_id = $data['companyId'];
        $this->account->balance = $data['balance'];
        $this->account->is_main = $data['isMain'];
        $this->account->currency = $data['currency'];
        $this->account->save();

        $success = false;
        if ($data['companyId'] != $originalData['company_id']) {
            $oldCompany = $redisService->invalidate('INVALIDATE_COMPANY', $originalData['company_id']);
            $newCompany = $redisService->invalidate('INVALIDATE_COMPANY', $data['companyId']);
            $success = $oldCompany && $newCompany;
        } else {
            $success = $redisService->invalidate('INVALIDATE_COMPANY', $company->id);
        }

        if (!$success) {
            $this->rollbackCompanyBankAccount($originalData, $mainAccount, $data['isMain']);
            return redirect()->route('admin.bank-accounts.render')->error(__('admin.toasts.bank_accounts.company.invalidate_bankaccount_api_error'));
        }

        return redirect()->route('admin.bank-accounts.render')->success(__('admin.toasts.bank_accounts.company.updated'));
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts.edit-company-bank-account');
    }

    private function rollbackCompanyBankAccount($originalData, $mainAccount, $isMain) {
        $this->account->company_id = $originalData['company_id'];
        $this->account->balance = $originalData['balance'];
        $this->account->is_main = $originalData['is_main'];
        $this->account->currency = $originalData['currency'];
        $this->account->save();

        if ($isMain == true && $mainAccount) {
            $mainAccount->is_main = true;
            $mainAccount->save();
        }
    }
}
