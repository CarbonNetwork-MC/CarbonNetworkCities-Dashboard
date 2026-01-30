<?php

namespace App\Livewire\Admin\Players;

use App\Http\Livewire\Concerns\WithInvalidation;
use App\Models\Country;
use App\Models\Player;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AddBankAccount extends Component
{
    use WithInvalidation;

    public $player;

    public $balance = 0;
    public $currency = 'EUR';
    public $type = 'savings';

    public $types = [
        'checking',
        'savings',
    ];
    public $currencies;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();

        $this->currencies = Country::get(['name', 'currency']);
    }

    public function addBankAccount() {
        $data = $this->validate([
            'balance' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'string', 'in:' . implode(',', $this->types)],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        // 1. Create Bank Account
        $bankAccount = $this->player->bankAccounts()->create([
            'player_uuid' => $this->player->uuid,
            'balance' => $data['balance'],
            'type' => $data['type'],
            'currency' => strtoupper($data['currency']),
        ]);

        // 2. Send invalidate request to Velocity
        /** @var Response $response */
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/player/{$this->player->uuid}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            $bankAccount->delete();
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.bank_account_add_failed'));
        }

        $requestId = $response->json()['requestId'];

        // 3. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $bankAccount->delete();
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.bank_account_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toast.players.bank_account_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-bank-account');
    }
}
