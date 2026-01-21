<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Services\PluginAPI\InvalidationService;

class Overview extends Component
{
    use WithPagination;

    public $searchUser = '';
    public $userName = '';
    public $userEmail = '';
    public $selectedLanguage = null;

    public $deleteUserModal = false;
    public $unlinkModal = false;

    public $selectedUser = null;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'searchUser') {
            $this->resetPage('page');
        }
    }

    // ? User Methods
    public function unlinkAccount($id) {
        $this->selectedUser = User::with('player')->find($id);
        $this->unlinkModal = true;
    }

    public function unlink() {
        if (!$this->selectedUser) return;

        $selectedUser = $this->selectedUser->load('player', 'accountLink');

        $this->selectedUser->accountLink()->delete();

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/player/{$selectedUser->player->uuid}");

        $requestId = $response->json('requestId');
            
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->selectedUser->accountLink()->save($selectedUser->accountLink());
            return Toaster::error(__('admin.toast.account_unlink_api_error'));
        }

        $this->reset([
            'selectedUser',
            'unlinkModal',
        ]);

        Toaster::success(__('admin.toast.users.account_unlinked'));
    }

    public function removeUser($id) {
        $this->selectedUser = User::find($id);
        $this->deleteUserModal = true;
    }

    public function destroyUser() {
        if ($this->selectedUser) {
            User::where('uuid', $this->selectedUser->uuid)->delete();
        } else {
            return Redirect::route('admin.users.render')
                ->error('User not found.');
        }

        $this->reset([
            'selectedUser',
            'deleteUserModal',
        ]);

        Toaster::success(__('admin.toast.users.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.users.overview', [
            'users' => User::where('name', 'like', '%' . $this->searchUser . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'languages' => Language::all(),
        ]);
    }

    private function waitForInvalidationResult(string $requestId): bool {
        return app(InvalidationService::class)->waitForInvalidationResult($requestId);
    }
}
