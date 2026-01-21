<?php

namespace App\Livewire\Admin\Users;

use App\Models\Language;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;

class EditUser extends Component
{
    public $user;
    public $userName;
    public $userEmail;
    public $selectedLanguage;

    public $languages;

    public function mount($uuid) {
        $this->user = User::find($uuid);
        $this->userName = $this->user->name;
        $this->userEmail = $this->user->email;
        $this->selectedLanguage = $this->user->selected_language;
        $this->languages = Language::all();
    }

    public function updateUser() {
        $data = $this->validate([
            'userName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')
                    ->ignore($this->user?->uuid, 'uuid'),
            ],
            'userEmail' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->user?->uuid, 'uuid'),
            ],
            'selectedLanguage' => [
                'nullable',
                'exists:languages,id',
            ],
        ]);

        $this->user->name = $data['userName'];
        $this->user->email = $data['userEmail'];
        $this->user->selected_language = !empty($data['selectedLanguage'])
            ? $data['selectedLanguage']
            : null;
        $this->user->save();

        return redirect()->route('admin.users.render')->success(__('admin.toast.user_updated'));
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user');
    }
}