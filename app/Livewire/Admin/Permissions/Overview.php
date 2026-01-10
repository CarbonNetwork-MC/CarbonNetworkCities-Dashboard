<?php

namespace App\Livewire\Admin\Permissions;

use App\Models\Permission;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Overview extends Component
{
    use WithPagination;

    public $searchPermission = '';
    public $permissionName = '';

    public $createPermissionModal = false;
    public $editPermissionModal = false;
    public $deletePermissionModal = false;

    public $selectedPermission = null;

    public function mount() {
        // 
    }

    public function updated($key, $value) {
        if ($key === 'searchPermission') {
            $this->resetPage();
        }
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

        $this->resetPage();
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

        $this->resetPage();
    }

    // ? Permission Group Methods

    public function render()
    {
        return view('livewire.admin.permissions.overview', [
            'permissions' => Permission::where('name', 'like', '%' . $this->searchPermission . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(1),
        ]);
    }
}
