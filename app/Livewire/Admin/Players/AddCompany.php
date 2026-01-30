<?php

namespace App\Livewire\Admin\Players;

use App\Http\Livewire\Concerns\WithInvalidation;
use App\Models\Company;
use App\Models\Player;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AddCompany extends Component
{
    use WithInvalidation;

    public $player;

    public $companyId;

    public $companies;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();

        $this->companies = Company::get(['id', 'name']);
    }

    public function addCompany() {
        $data = $this->validate([
            'companyId' => ['required', 'exists:companies,id'],
        ]);

        // Store the company to add for rollback in case of failure
        $company = Company::where('id', $data['companyId'])->first();
        $originalCompany = $company->replicate();

        // 1. Optimistic Update
        $company->update([
            'owner_uuid' => $this->player->uuid,
        ]);

        // 2. Send invalidate request to Velocity
        /** @var Response $response */
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/company/{$company->id}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            $this->rollbackCompany($company, $originalCompany);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.company_add_failed'));
        }

        $requestId = $response->json()['requestId'];

        // 3. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->rollbackCompany($company, $originalCompany);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.company_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toast.players.company_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-company');
    }

    private function rollbackCompany($company, $originalCompany) {
        $company->update([
            'owner_uuid' => $originalCompany->owner_uuid,
        ]);
    }
}
