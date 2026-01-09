<?php

namespace App\Livewire\Onboarding;

use App\Models\AccountLink;
use App\Models\AccountLinkToken;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Carbon;

class Onboarding extends Component
{
    public $user;
    public $numOfSteps = 3;
    public $currentStep = 1;

    public $code = '';
    public $validCode = false;
    public $errorMessage = '';

    public function mount() {
        $this->user = auth()->user();
    }

    public function previousStep() {
        if ($this->user->onboarding_step > 1) {
            $this->user->onboarding_step--;
            $this->user->save();
        }
    }

    public function submitStep1() {
        $this->user->onboarding_step = 2;
        $this->user->save();
    }

    public function submitStep2() {
        $this->checkCode();

        if (!$this->validCode) return;

        $this->user->onboarding_step = 3;
        $this->user->save();
    }

    public function submitStep3() {
        $this->user->onboarding_status = 0;
        $this->user->save();
        return redirect()->route('dashboard.render');
    }

    private function checkCode() {
        $this->resetMessages();

        $token = AccountLinkToken::where('token', $this->code)->first();

        if (!$token) return $this->fail('onboarding.incorrect_code');
        if ($token->used_at !== null) return $this->fail('onboarding.code_already_used');
        if ($token->expires_at->isPast()) return $this->fail('onboarding.code_expired');
        if (AccountLink::where('player_uuid', $token->player_uuid)->where('is_linked', true)->exists()) {
            return $this->fail('onboarding.player_already_linked');
        }

        $success = AccountLink::create([
            'player_uuid' => $token->player_uuid,
            'user_uuid' => $this->user->uuid,
            'is_linked' => true,
            'linked_at' => Carbon::now(),
        ]);

        if (!$success) return $this->fail('onboarding.incorrect_code');

        $token->update([
            'used_at' => Carbon::now(),
        ]);

        $this->validCode = true;
        $this->errorMessage = '';
    }

    #[Layout('layouts.onboarding')]
    public function render()
    {
        return view('livewire.onboarding.onboarding');
    }

    private function resetMessages(): void
    {
        $this->validCode = false;
        $this->errorMessage = '';
    }

    private function fail(string $message): void
    {
        $this->validCode = false;
        $this->errorMessage = $message;
    }
}
