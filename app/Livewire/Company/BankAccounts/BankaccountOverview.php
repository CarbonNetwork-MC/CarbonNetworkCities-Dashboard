<?php

namespace App\Livewire\Company\BankAccounts;

use App\Models\BankTransaction;
use App\Models\Company;
use App\Models\CompanyBankaccount;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class BankaccountOverview extends Component
{
    use WithPagination;

    public $company;
    public $bankAccount;

    public $currencySymbol;

    public function mount($companyId, $bankAccountId) {
        $this->company = Company::find($companyId);
        $this->bankAccount = CompanyBankaccount::find($bankAccountId);

        $this->currencySymbol = Country::where('currency', $this->bankAccount->currency)->first()->currency_symbol ?? $this->bankAccount->currency;
    }

    public function render()
    {
        $transactions = BankTransaction::query()
            ->where(function ($q) {
                $q->where('from_company_id', $this->bankAccount->id)
                ->orWhere('to_company_id', $this->bankAccount->id);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.company.bank-accounts.bankaccount-overview', [
            'transactions' => $transactions,
        ]);
    }
}
