<?php

namespace App\Livewire\Admin\BankAccounts;

use App\Models\Player;
use App\Models\Country;
use Livewire\Component;
use App\Models\PersonalBankaccount;
use App\Services\RedisService;

class EditPersonalBankAccount extends Component
{
    public $account;

    public $players;
    public $countries;

    public $playerUuid;
    public $balance;
    public $type;
    public $currency;

    public function mount($id) {
        $this->account = PersonalBankaccount::findOrFail($id);

        $this->playerUuid = $this->account->player_uuid;
        $this->balance = $this->account->balance;
        $this->type = ucfirst($this->account->type);
        $this->currency = $this->account->currency;

        $this->players = Player::get(['uuid', 'username']);
        $this->countries = Country::get(['name', 'currency']);
    }

    public function updatePersonalBankAccount(RedisService $redisService) {
        if (!$this->account) return;

        $originalData = $this->account->toArray();

        $currencies = Country::pluck('currency')->toArray();

        $data = $this->validate([
            'playerUuid' => ['required', 'string', 'exists:players,uuid'],
            'balance' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:Checking,Savings'],
            'currency' => ['required', 'string', 'in:' . implode(',', $currencies)],
        ]);

        $this->account->player_uuid = $data['playerUuid'];
        $this->account->balance = $data['balance'];
        $this->account->type = strtolower($data['type']);
        $this->account->currency = $data['currency'];
        $this->account->save();

        $success = false;
        if ($data['playerUuid'] !== $originalData['player_uuid']) {
            $oldPlayer = $redisService->invalidate('INVALIDATE_PLAYER', $originalData['player_uuid']);
            $newPlayer = $redisService->invalidate('INVALIDATE_PLAYER', $data['playerUuid']);
            $success = $oldPlayer && $newPlayer;
        } else {
            $success = $redisService->invalidate('INVALIDATE_PLAYER', $data['playerUuid']);
        }

        if (!$success) {
            $this->rollbackPersonalBankAccount($originalData);
            return redirect()->route('admin.bank-accounts.personal.edit', ['id' => $this->account->id])->error(__('admin.toasts.bank_accounts.personal.invalidate_bankaccount_api_error'));
        }

        return redirect()->route('admin.bank-accounts.render')->success(__('admin.toasts.bank_account.personal.updated'));
    }
    
    public function render()
    {
        return view('livewire.admin.bank-accounts.edit-personal-bank-account');
    }

    private function rollbackPersonalBankAccount(array $originalData) {
        $this->account->player_uuid = $originalData['player_uuid'];
        $this->account->balance = $originalData['balance'];
        $this->account->type = $originalData['type'];
        $this->account->currency = $originalData['currency'];
        $this->account->save();
    }
}
