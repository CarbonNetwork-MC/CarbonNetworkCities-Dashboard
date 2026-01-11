<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Permission;
use Livewire\Component;

class EditPermission extends Component
{
    public $permission;
    public $permissionName;

    public function mount($uuid) {
        $this->permission = Permission::where('uuid', $uuid)->firstOrFail();
        $this->permissionName = $this->permission->name;
    }

    public function updatePermission() {
        $data = $this->validate([
            'permissionName' => ['required', 'string', 'max:255'],
        ]);

        $this->permission->name = $data['permissionName'];
        $this->permission->save();

        return redirect()->route('admin.roles-perms.render')->success(__('admin.toast.permission_updated'));
    }

    public function render()
    {
        return view('livewire.admin.roles-perms.edit-permission');
    }
}
