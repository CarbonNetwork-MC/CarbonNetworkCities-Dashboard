<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class EditRole extends Component
{
    public $role;
    public $roleName;
    public $rolePermissions;
    public $assignablePermissions;

    public $search = '';

    public $selectedPermission = null;
    public $permissionToRemove = null;

    public $assignPermissionModal = false;
    public $removePermissionModal = false;

    public function mount($uuid) {
        $this->role = Role::where('uuid', $uuid)->firstOrFail();
        $this->roleName = $this->role->name;
        $this->rolePermissions = $this->role->permissions;
        $this->assignablePermissions = Permission::whereNotIn('uuid', $this->rolePermissions->pluck('uuid')->toArray())->get();
    }

    public function updateRole() {
        $data = $this->validate([
            'roleName' => ['required', 'string', 'max:255'],
        ]);

        $this->role->name = $data['roleName'];
        $this->role->save();

        return redirect()->route('admin.roles-perms.render')->success(__('admin.toast.role_updated'));
    }

    public function addPermission() {
        $data = $this->validate([
            'selectedPermission' => ['required', 'exists:panel_permissions,uuid'],
        ]);

        $permission = Permission::where('uuid', $data['selectedPermission'])->first();
        if ($permission && !$this->rolePermissions->contains('uuid', $permission->uuid)) {
            $this->role->permissions()->attach($permission->uuid);
            $this->rolePermissions->push($permission);
            $this->assignablePermissions = Permission::whereNotIn('uuid', $this->rolePermissions->pluck('uuid')->toArray())->get();
        }

        $this->assignPermissionModal = false;
        $this->selectedPermission = null;

        Toaster::success(__('admin.toast.role_permission_added'));
    }

    public function removePermission($uuid) {
        $this->permissionToRemove = Permission::where('uuid', $uuid)->first();
        $this->removePermissionModal = true;
    }

    public function destroyPermission() {
        if ($this->permissionToRemove && $this->rolePermissions->contains('uuid', $this->permissionToRemove->uuid)) {
            $this->role->permissions()->detach($this->permissionToRemove->uuid);
            $this->rolePermissions = $this->rolePermissions->reject(fn($permission) => $permission->uuid === $this->permissionToRemove->uuid);
            $this->assignablePermissions = Permission::whereNotIn('uuid', $this->rolePermissions->pluck('uuid')->toArray())->get();
        }

        $this->removePermissionModal = false;
        $this->permissionToRemove = null;

        Toaster::success(__('admin.toast.role_permission_removed'));
    }

    public function render()
    {
        return view('livewire.admin.roles-perms.edit-role');
    }
}
