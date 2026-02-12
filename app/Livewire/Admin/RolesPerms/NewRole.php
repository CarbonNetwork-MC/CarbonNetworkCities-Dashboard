<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Role;
use Livewire\Component;

class NewRole extends Component
{
    public $roleName = '';

    public function createRole() {
        $data = $this->validate([
            'roleName' => ['required', 'string', 'max:255']
        ]);

        Role::create([
            'name' => $data['roleName'],
        ]);

        return redirect()->route('admin.roles-perms.render')->success(__('admin.toasts.roles.created'));
    }

    public function render()
    {
        return view('livewire.admin.roles-perms.new-role');
    }
}
