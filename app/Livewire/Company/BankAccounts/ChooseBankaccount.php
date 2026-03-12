<?php

namespace App\Livewire\Company\BankAccounts;

use App\Models\Company;
use App\Models\CompanyBankaccount;
use Livewire\Component;

class ChooseBankaccount extends Component
{
    public $company;
    public $bankAccounts;

    public function mount($companyId) {
        $this->company = Company::find($companyId);
        $this->bankAccounts = CompanyBankaccount::where('company_id', $companyId)->with('country')->get();
    }

    public function selectBankAccount($id) {
        return redirect()->route('company.bank-account.render', ['companyId' => $this->company->id, 'bankAccountId' => $id]);
    }
    public function render()
    {
        return view('livewire.company.bank-accounts.choose-bankaccount');
    }
}
