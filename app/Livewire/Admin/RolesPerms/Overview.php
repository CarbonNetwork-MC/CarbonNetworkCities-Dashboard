<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $searchPermission = '';
    public $searchRole = '';

    public $rolesPerPage = 5;
    public $permissionsPerPage = 10;

    public $deletePermissionModal = false;
    public $deleteRoleModal = false;

    public $selectedPermission = null;
    public $selectedRole = null;

    // ? Pagination Methods
    public function updated($key, $value) {
        if ($key === 'searchPermission') {
            $this->resetPage('permissionsPage');
        }

        if ($key === 'searchRole') {
            $this->resetPage('rolesPage');
        }
    }

    // ? Permission Methods
    public function removePermission($uuid) {
        $this->selectedPermission = Permission::find($uuid);
        $this->deletePermissionModal = true;
    }

    public function destroyPermission() {
        if ($this->selectedPermission) {
            Permission::where('uuid', $this->selectedPermission->uuid)->delete();
        }

        $this->reset([
            'selectedPermission',
            'deletePermissionModal',
        ]);

        Toaster::success(__('admin.toast.permissions.deleted'));
    }

    // ? Role Methods
    public function removeRole($uuid) {
        $this->selectedRole = Role::where('uuid', $uuid)->first();
        $this->deleteRoleModal = true;
    }

    public function destroyRole() {
        if ($this->selectedRole) {
            Role::where('uuid', $this->selectedRole->uuid)->delete();
        }

        $this->reset([
            'selectedRole',
            'deleteRoleModal',
        ]);

        Toaster::success(__('admin.toast.roles.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.roles-perms.overview', [
            'permissions' => Permission::where('name', 'like', '%' . $this->searchPermission . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($this->permissionsPerPage, pageName: 'permissionsPage'),
            'roles' => Role::where('name', 'like', '%' . $this->searchRole . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($this->rolesPerPage, pageName: 'rolesPage'),
        ]);
    }
}