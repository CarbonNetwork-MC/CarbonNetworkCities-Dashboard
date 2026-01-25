<?php

namespace App\Livewire\Admin\Users;

use App\Models\Language;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Masmerise\Toaster\Toaster;

class EditUser extends Component
{
    use WithPagination;

    public $user;
    public $userName;
    public $userEmail;
    public $selectedLanguage;

    public $availableRoles;
    public $availablePermissions;

    public $selectedRole = null;
    public $selectedPermission = null;

    public $rolesPerPage = 5;
    public $permissionsPerPage = 10;

    public $roleToRemove = null;
    public $permissionToRemove = null;

    public $assignRoleModal = false;
    public $assignPermissionModal = false;
    public $removeRoleModal = false;
    public $removePermissionModal = false;

    public $languages;

    public function mount($uuid) {
        $this->user = User::find($uuid);
        $this->userName = $this->user->name;
        $this->userEmail = $this->user->email;
        $this->selectedLanguage = $this->user->selected_language;
        $this->languages = Language::all();

        $this->availableRoles = Role::whereNotIn('uuid', function($query) {
            $query->select('role_id')
                ->from('panel_model_has_roles')
                ->where('model_uuid', $this->user->uuid)
                ->where('model_type', User::class);
        })->get();

        $this->availablePermissions = Permission::whereNotIn('uuid', function($query) {
            $query->select('permission_id')
                ->from('panel_model_has_permissions')
                ->where('model_uuid', $this->user->uuid)
                ->where('model_type', User::class);
        })->get();
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

        return redirect()->route('admin.users.render')->success(__('admin.toast.users.updated'));
    }

    public function assignUserRole() {
        if (!$this->selectedRole) return;

        $role = Role::where('uuid', $this->selectedRole)->first();
        if (!$role) return;

        $this->user->assignRole($role);
        $this->assignRoleModal = false;
        $this->selectedRole = null;
        $this->availableRoles = $this->availableRoles->filter(fn($r) => $r->uuid !== $role->uuid);

        Toaster::success(__('admin.toast.users.role_assigned', ['role' => $role->name]));
    }

    public function assignUserPermission() {
        if (!$this->selectedPermission) return;

        $permission = Permission::where('uuid', $this->selectedPermission)->first();
        if (!$permission) return;

        $this->user->givePermissionTo($permission);
        $this->assignPermissionModal = false;
        $this->selectedPermission = null;
        $this->availablePermissions = $this->availablePermissions->filter(fn($p) => $p->uuid !== $permission->uuid);

        Toaster::success(__('admin.toast.users.permission_assigned', ['permission' => $permission->name]));
    }

    public function removeUserRole($uuid) {
        $this->roleToRemove = Role::where('uuid', $uuid)->first();
        $this->removeRoleModal = true;
    }

    public function destroyUserRole() {
        if (!$this->roleToRemove) return;

        $this->user->removeRole($this->roleToRemove);
        $this->availableRoles = $this->availableRoles->filter(fn($r) => $r->uuid !== $this->roleToRemove->uuid);
        $this->roleToRemove = null;
        $this->removeRoleModal = false;

        Toaster::success(__('admin.toast.users.role_removed'));
    }

    public function removeUserPermission($uuid) {
        $this->permissionToRemove = Permission::where('uuid', $uuid)->first();
        $this->removePermissionModal = true;
    }

    public function destroyUserPermission() {
        if (!$this->permissionToRemove) return;

        $this->user->revokePermissionTo($this->permissionToRemove);
        $this->availablePermissions = $this->availablePermissions->filter(fn($p) => $p->uuid !== $this->permissionToRemove->uuid);
        $this->permissionToRemove = null;
        $this->removePermissionModal = false;

        Toaster::success(__('admin.toast.users.permission_removed'));
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user', [
            'userRoles' => $this->user->roles()->paginate($this->rolesPerPage, ['*'], 'roles'),
            'userPermissions' => $this->user->permissions()->paginate($this->permissionsPerPage, ['*'], 'permissions'),
        ]);
    }
}