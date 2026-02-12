<?php

namespace App\Livewire\Admin\BankAccounts;

use App\Models\Player;
use App\Models\Country;
use App\Models\PersonalBankaccount;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class NewPersonalBankAccount extends Component
{
    public $players;
    public $countries;

    public $playerUuid;
    public $type;
    public $currency;

    public function mount() {
        $this->players = Player::get(['uuid', 'username']);
        $this->countries = Country::get(['name', 'currency']);
    }

    public function createPersonalBankAccount(ApiService $apiService) {
        $currencies = Country::pluck('currency')->toArray();

        $data = $this->validate([
            'playerUuid' => ['required', 'string', 'exists:players,uuid'],
            'type' => ['required', 'string', 'in:Checking,Savings'],
            'currency' => ['required', 'string', 'in:' . implode(',', $currencies)],
        ]);

        $personalBankAccount = PersonalBankaccount::create([
            'player_uuid' => $data['playerUuid'],
            'balance' => 0,
            'type' => strtolower($data['type']),
            'currency' => $data['currency'],
        ]);

        [$status, $success] = $apiService->post("api/invalidate/player/{$this->playerUuid}");

        if ($status !== 202) {
            $personalBankAccount->delete();
            return redirect()->route('admin.bank-accounts.personal.new')->error(__('admin.toasts.bank_accounts.personal.invalidate_bankaccount_api_error'));
        }

        if (!$success) {
            $personalBankAccount->delete();
            return redirect()->route('admin.bank-accounts.personal.new')->error(__('admin.toasts.bank_accounts.personal.invalidate_bankaccount_api_error'));
        }

        return redirect()->route('admin.bank-accounts.render')->success(__('admin.toasts.bank_account.personal.created'));
    }

    public function render()
    {
        return view('livewire.admin.bank-accounts.new-personal-bank-account');
    }
}
