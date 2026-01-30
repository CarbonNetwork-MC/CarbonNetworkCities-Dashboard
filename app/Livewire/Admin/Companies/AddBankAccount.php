<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Country;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class AddBankAccount extends Component
{    
    public $company;

    public $balance = 0;
    public $currency;
    public $isMain = false;

    public $currencies;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
        $this->currencies = Country::distinct()->orderBy('currency')->pluck('currency');
    }

    public function addBankAccount(ApiService $apiService) {
        $data = $this->validate([
            'balance' => 'required|numeric|min:0',
            'currency' => 'required|string|in:' . $this->currencies->implode(','),
            'isMain' => 'boolean',
        ]);

        // Store the current data for rollback in case of failure
        $originalBankAccounts = $this->company->bankAccounts()->get(['id', 'is_main']);

        // 1. Create Bank Account
        $bankAccount = $this->company->bankAccounts()->create([
            'company_id' => $this->company->id,
            'balance' => $data['balance'],
            'is_main' => $data['isMain'],
            'currency' => $data['currency'],
        ]);

        // Set all other accounts to not main
        if ($data['isMain']) {
            $this->company->bankAccounts()->where('id', '!=', $bankAccount->id)->update(['is_main' => false]);
        }

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$this->company->id}");

        // Immediate failure (request not accepted)
        if ($status !== 202) {
            $this->rollbackBankAccounts($bankAccount, $originalBankAccounts);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.companies.bank_account_add_failed'));
        }

        // 3. Poll for result
        if (!$success) {
            $this->rollbackBankAccounts($bankAccount, $originalBankAccounts);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.companies.bank_account_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.companies.bank_account_added'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-bank-account');
    }

    private function rollbackBankAccounts($bankAccount, $originalBankAccounts) {
        $bankAccount->delete();

        // Restore original bank accounts' is_main status
        foreach ($originalBankAccounts as $originalAccount) {
            $this->company->bankAccounts()->updateOrCreate(
                ['id' => $originalAccount->id],
                ['is_main' => $originalAccount->is_main]
            );
        }
    }
}
