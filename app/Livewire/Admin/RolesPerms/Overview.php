<?php

namespace App\Livewire\Admin\RolesPerms;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $user;

    public $searchPermission = '';
    public $searchRole = '';
    public $permissionName = '';

    public $createPermissionModal = false;
    public $editPermissionModal = false;
    public $deletePermissionModal = false;
    public $deleteRoleModal = false;

    public $selectedPermission = null;

    public function mount() {
        $this->user = auth()->user();
    }

    // ? Permission Methods
    public function savePermission() {
        $this->validate([
            'permissionName' => ['required', 'string', 'max:255', 'unique:panel_permissions,name']
        ]);

        Permission::create([
            'name' => $this->permissionName,
        ]);

        $this->permissionName = '';
        $this->createPermissionModal = false;

        Toaster::success(__('admin.toast.permissions_created'));
    }

    public function editPermission($id) {
        $this->selectedPermission = Permission::find($id);
        $this->permissionName = $this->selectedPermission->name;
        $this->editPermissionModal = true;
    }

    public function updatePermission() {
        $this->validate([
            'permissionName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('panel_permissions', 'name')
                    ->ignore($this->selectedPermission?->uuid, 'uuid'),
            ]
        ]);

        if ($this->selectedPermission) {
            $this->selectedPermission->update([
                'name' => $this->permissionName,
            ]);
        }

        $this->reset([
            'permissionName',
            'selectedPermission',
            'editPermissionModal',
        ]);

        Toaster::success(__('admin.toast.permissions_updated'));
    }

    public function removePermission($id) {
        $this->selectedPermission = Permission::find($id);
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

        Toaster::success(__('admin.toast.permissions_deleted'));
    }

    // ? Permission Group Methods

    public function render()
    {
        return view('livewire.admin.roles-perms.overview', [
            'permissions' => Permission::where('name', 'like', '%' . $this->searchPermission . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'roles' => Role::where('name', 'like', '%' . $this->searchRole . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
}