<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class Overview extends Component
{
    use WithPagination;

    public $searchUser = '';
    public $userName = '';
    public $userEmail = '';
    public $selectedLanguage = null;

    public $createUserModal = false;
    public $editUserModal = false;
    public $deleteUserModal = false;
    public $unlinkModal = false;

    public $selectedUser = null;

    public function mount() {
        // 
    }

    // ? User Methods
    public function unlinkAccount($id) {
        $this->selectedUser = User::find($id);
        $this->unlinkModal = true;
    }

    public function unlink() {
        if ($this->selectedUser) {
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

        return Redirect::route('admin.users.render')
            ->success('User account unlinked successfully.');
    }

    public function editUser($id) {
        $this->selectedUser = User::find($id);

        if (!$this->selectedUser) {
            return Redirect::route('admin.users.render')
                ->error('User not found.');
        }

        $this->userName = $this->selectedUser->name;
        $this->userEmail = $this->selectedUser->email;
        $this->selectedLanguage = $this->selectedUser->selected_language;
        $this->editUserModal = true;
    }

    public function updateUser() {
        $this->validate([
            'userName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')
                    ->ignore($this->selectedUser?->uuid, 'uuid'),
            ],
            'userEmail' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->selectedUser?->uuid, 'uuid'),
            ],
            'selectedLanguage' => [
                'nullable',
                'exists:languages,id',
            ],
        ]);

        if ($this->selectedUser) {
            $this->selectedUser->update([
                'name' => $this->userName,
                'email' => $this->userEmail,
                'selected_language' => $this->selectedLanguage,
            ]);
        } else {
            return Redirect::route('admin.users.render')
                ->error('User not found.');
        }

        $this->reset([
            'userName',
            'userEmail',
            'selectedLanguage',
            'selectedUser',
            'editUserModal',
        ]);

        return Redirect::route('admin.users.render')
            ->success('User updated successfully.');
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

        return Redirect::route('admin.users.render')
            ->success('User deleted successfully.');
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
