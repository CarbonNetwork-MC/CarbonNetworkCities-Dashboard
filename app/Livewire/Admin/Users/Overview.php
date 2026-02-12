<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Services\PluginAPI\ApiService;
use Illuminate\Support\Facades\Redirect;

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

    public function unlink(ApiService $apiService) {
        if (!$this->selectedUser) return;

        $selectedUser = $this->selectedUser->load('player', 'accountLink');

        $this->selectedUser->accountLink()->delete();

        [$status, $success] = $apiService->post("api/invalidate/player/{$selectedUser->player->uuid}");

        if (!$success) {
            $this->selectedUser->accountLink()->save($selectedUser->accountLink());
            return Toaster::error(__('admin.toasts.account_unlink_api_error'));
        }

        $this->reset([
            'selectedUser',
            'unlinkModal',
        ]);

        Toaster::success(__('admin.toasts.users.account_unlinked'));
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

        Toaster::success(__('admin.toasts.users.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.users.overview', [
            'users' => User::where('name', 'like', '%' . $this->searchUser . '%')
                ->orderBy('created_at', 'asc')
                ->paginate(10),
        ]);
    }
}
