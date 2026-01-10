<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use App\Models\Language;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

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

    public $selectedUser = null;

    public function mount() {
        // 
    }

    public function editUser($id) {
        $this->selectedUser = User::find($id);
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
        }

        $this->reset([
            'userName',
            'userEmail',
            'selectedLanguage',
            'selectedUser',
            'editUserModal',
        ]);

        $this->resetPage();
    }

    public function removeUser($id) {
        $this->selectedUser = User::find($id);
        $this->deleteUserModal = true;
    }

    public function destroyUser() {
        if ($this->selectedUser) {
            User::where('uuid', $this->selectedUser->uuid)->delete();
        }

        $this->reset([
            'selectedUser',
            'deleteUserModal',
        ]);

        $this->resetPage();
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
