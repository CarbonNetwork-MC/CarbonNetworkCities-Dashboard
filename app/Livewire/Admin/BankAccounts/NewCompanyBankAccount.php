<?php

namespace App\Livewire\Admin\BankAccounts;

use App\Models\Company;
use App\Models\Country;
use Livewire\Component;
use App\Models\CompanyBankaccount;
use App\Services\PluginAPI\ApiService;

class NewCompanyBankAccount extends Component
{
    public $companies;
    public $countries;

    public $companyId;
    public $isMain = false;
    public $currency;

    public function mount() {
        $this->companies = Company::get(['id', 'name']);
        $this->countries = Country::get(['name', 'currency']);
    }

    public function createCompanyBankAccount(ApiService $apiService) {
        $currencies = Country::pluck('currency')->toArray();

        $data = $this->validate([
            'companyId' => ['required', 'integer'],
            'isMain' => ['boolean'],
            'currency' => ['required', 'string', 'in:' . implode(',', $currencies)],
        ]);

        $company = Company::find($data['companyId']);
        $mainAccount = $company->bankAccounts->where('is_main', true)->first();

        if ($data['isMain'] == true && $mainAccount) {
            $mainAccount->is_main = false;
            $mainAccount->save();
        }

        $companyBankAccount = CompanyBankaccount::create([
            'company_id' => $data['companyId'],
            'balance' => 0,
            'is_main' => $data['isMain'] ?? false,
            'currency' => $data['currency'],
        ]);

        [$status, $success] = $apiService->post("api/invalidate/company/{$company->id}");

        if ($status !== 202) {
            $this->rollbackCreateCompanyBankAccount($companyBankAccount, $mainAccount, $data['isMain']);
        }

        if (!$success) {
            $this->rollbackCreateCompanyBankAccount($companyBankAccount, $mainAccount, $data['isMain']);
        }

        return redirect()->route('admin.bank-accounts.render')->success(__('admin.toasts.bank_accounts.company.created'));
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts.new-company-bank-account');
    }

    private function rollbackCreateCompanyBankAccount($companyBankAccount, $mainAccount, $isMain) {
        $companyBankAccount->delete();

        if ($isMain == true && $mainAccount) {
            $mainAccount->is_main = true;
            $mainAccount->save();
        }

        return redirect()->route('admin.bank-accounts.company.new')->error(__('admin.toasts.bank_accounts.company.invalidate_bankaccount_api_error'));
    }   
}
