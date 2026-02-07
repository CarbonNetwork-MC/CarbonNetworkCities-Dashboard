<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class EditRole extends Component
{
    use WithPagination;

    public $role;
    public $roleName;

    public $search = '';
    public $permissionsPerPage = 10;

    public $selectedPermission = null;
    public $permissionToRemove = null;

    public $assignPermissionModal = false;
    public $removePermissionModal = false;

    public function mount($uuid) {
        $this->role = Role::where('uuid', $uuid)->firstOrFail();
        $this->roleName = $this->role->name;
    }

    public function updatedSearch()
    {
        $this->resetPage('permissions');
    }

    public function updateRole() {
        $data = $this->validate([
            'roleName' => ['required', 'string', 'max:255'],
        ]);

        $this->role->name = $data['roleName'];
        $this->role->save();

        return redirect()->route('admin.roles-perms.render')->success(__('admin.toasts.roles.updated'));
    }

    public function addPermission() {
        $data = $this->validate([
            'selectedPermission' => ['required', 'exists:panel_permissions,uuid'],
        ]);

        $permission = Permission::where('uuid', $data['selectedPermission'])->first();
        if (!$permission) return;

        $alreadyAttached = $this->role->permissions()
            ->where('permissions.uuid', $permission->uuid)
            ->exists();

        if (!$alreadyAttached) {
            $this->role->permissions()->attach($permission->uuid);
        }

        $this->assignPermissionModal = false;
        $this->selectedPermission = null;

        Toaster::success(__('admin.toasts.roles.permission_added'));
    }

    public function removePermission($uuid) {
        $this->permissionToRemove = Permission::where('uuid', $uuid)->first();
        $this->removePermissionModal = true;
    }

    public function destroyPermission() {
        if (!$this->permissionToRemove) return;

        $this->role->permissions()->detach($this->permissionToRemove->uuid);

        $this->removePermissionModal = false;
        $this->permissionToRemove = null;

        Toaster::success(__('admin.toasts.roles.permission_removed'));
    }

    public function render()
    {
        return view('livewire.admin.roles-perms.edit-role', [
            'rolePermissions' => $this->role->permissions()->orderBy('name')->paginate($this->permissionsPerPage, ['*'], 'permissions'),
            'assignablePermissions' => Permission::whereDoesntHave('roles', function ($q) {
                $q->where('panel_roles.uuid', $this->role->uuid);
            })
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->get(['uuid', 'name']),
        ]);
    }
}
