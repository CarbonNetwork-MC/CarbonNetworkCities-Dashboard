<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
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

    // ? User Methods
    public function unlinkAccount($id) {
        $this->selectedUser = User::find($id);
        $this->unlinkModal = true;
    }

    public function unlink() {
        if ($this->selectedUser) {

            try {
                $url = config('services.plugin-api.url') . '/invalidate-player/' . $this->selectedUser->player->uuid;

                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::withToken(
                    config('services.plugin-api.key')
                )->post($url);

                if ($response->status() == 400) {
                    Toaster::error(__('admin.toast.account_unlink_missing_player_error'));
                } elseif ($response->status() == 401) {
                    Toaster::error(__('admin.toast.api_unauthorized_error'));
                } elseif ($response->failed()) {
                    Toaster::error(__('admin.toast.account_unlink_api_error'));
                    return;
                }
            } catch (\Exception $e) {
                Toaster::error(__('admin.toast.account_unlink_api_error'));
                return;
            }
            $this->selectedUser->update([
                'onboarding_status' => 1,
                'onboarding_step' => 1,
            ]);

            if ($this->selectedUser->accountLink()) {
                $this->selectedUser->accountLink()->delete();
            }

        } else {
            return Redirect::route('admin.users.render')
                ->error('User not found.');
        }

        $this->reset([
            'selectedUser',
            'unlinkModal',
        ]);

        Toaster::success(__('admin.toast.account_unlinked'));
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

        Toaster::success(__('admin.toast.user_deleted'));
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
}
