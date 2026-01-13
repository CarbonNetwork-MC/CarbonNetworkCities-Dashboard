<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Country;
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
        $this->currencies = Country::all()->pluck('currency')->unique()->sort()->values();
    }

    public function addBankAccount() {
        $data = $this->validate([
            'balance' => 'required|numeric|min:0',
            'currency' => 'required|string|in:' . $this->currencies->implode(','),
            'isMain' => 'boolean',
        ]);

        // Set all other accounts to not main
        if ($data['isMain']) {
            $this->company->bankAccounts()->update(['is_main' => false]);
        }

        $this->company->bankAccounts()->create([
            'company_id' => $this->company->id,
            'balance' => $data['balance'],
            'is_main' => $data['isMain'],
            'currency' => $data['currency'],
        ]);

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.company_bank_account_added'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-bank-account');
    }
}
