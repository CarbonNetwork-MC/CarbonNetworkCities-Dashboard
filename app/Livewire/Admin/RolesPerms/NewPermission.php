<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Permission;
use Livewire\Component;

class NewPermission extends Component
{
    public $permissionName = '';

    public function createPermission() {
        $data = $this->validate([
            'permissionName' => ['required', 'string', 'max:255']
        ]);

        Permission::create([
            'name' => $data['permissionName'],
        ]);

        return redirect()->route('admin.roles-perms.render')->success(__('admin.toast.permission_created'));
    }

    public function render()
    {
        return view('livewire.admin.roles-perms.new-permission');
    }
}
