<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url'  => route('admin.dashboard.render'),
                'label'=> '',
            ],
            [
                'url'   => route('admin.users.render'),
                'label' => __('sidebar.users'),
            ],
            [
                'url'   => route('admin.users.edit', ['uuid' => $user->uuid]),
                'label' => __('admin.titles.users.edit'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('general.buttons.edit') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('general.labels.name') }}" wire:model="userName" required class="mb-5"/>
                </div>
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('general.labels.email') }}" wire:model="userEmail" required class="mb-5"/>
                </div>
                <div class="col-span-2"></div>
                <div class="col-span-1">
                    <x-forms.label for="language" required>
                        {{ __('admin.labels.users.select_language') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="language"
                        :options="$languages->map(fn($language) => ['value' => $language->id, 'label' => $language->name])"
                        wire:model="selectedLanguage"
                        :min-search-length="2"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="updateUser">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.users.roles') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchRole" wire:model.live="searchRole" class="w-full" />
                <x-buttons.primary-button size="sm" wire:click="$set('assignRoleModal', true)">
                    {{ __('admin.buttons.users.assign_role') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.roles.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.roles.permissions') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($userRoles as $role)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $role->name }}</x-tables.table-data>
                            <x-tables.table-data>
                                @forelse ($role->permissions as $permission)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-gray-600 dark:text-gray-200 italic">{{ __('admin.labels.roles.no_permissions_assigned') }}</span>
                                @endforelse
                            </x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removeUserRole('{{ $role->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="3">
                                {{ __('admin.messages.users.no_roles_assigned') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($userRoles->hasPages())
                        <div class="flex items-center gap-x-4 mt-4">
                            {{ $user->roles->links() }}
                            <x-tables.per-page-select wire:model.live="rolesPerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </x-tables.per-page-select>
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.users.permissions') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchPermission" wire:model.live="searchPermission" class="w-full" />
                <x-buttons.primary-button size="sm" wire:click="$set('assignPermissionModal', true)">
                    {{ __('admin.buttons.users.assign_permission') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.permissions.name') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($userPermissions as $permission)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $permission->name }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removeUserPermission('{{ $permission->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="3">
                                {{ __('admin.messages.users.no_permissions_assigned') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($userPermissions->hasPages())
                        <div class="flex items-center gap-x-4 mt-4">
                            {{ $userPermissions->links() }}
                            <x-tables.per-page-select wire:model.live="permissionsPerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </x-tables.per-page-select>
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Assign Role Modal --}}
    <x-modals.modal wire:model="assignRoleModal">
        <x-slot name="title">{{ __('admin.buttons.users.assign_role') }}</x-slot>
        <x-slot name="content">
            <livewire:async-select 
                id="role"
                :options="$availableRoles->map(fn($role) => ['value' => $role->uuid, 'label' => $role->name])"
                wire:model="selectedRole"
                :min-search-length="2"
            />
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('assignRoleModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.primary-button wire:click="assignUserRole">
                {{ __('general.buttons.assign') }}
            </x-buttons.primary-button>
        </x-slot>
    </x-modals.modal>

    {{-- Assign Permission Modal --}}
    <x-modals.modal wire:model="assignPermissionModal">
        <x-slot name="title">{{ __('admin.buttons.users.assign_permission') }}</x-slot>
        <x-slot name="content">
            <livewire:async-select
                    id="permission"
                    :options="$availablePermissions->map(fn($permission) => ['value' => $permission->uuid, 'label' => $permission->name])"
                    wire:model="selectedPermission"
                    :min-search-length="2"
            />
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('assignPermissionModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.primary-button wire:click="assignUserPermission">
                {{ __('general.buttons.assign') }}
            </x-buttons.primary-button>
        </x-slot>
    </x-modals.modal>

    {{-- Remove Permission Modal --}}
    <x-modals.modal wire:model="removePermissionModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('general.buttons.delete') }}</div></x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.users.delete_permission_confirmation', ['permission' => $permissionToRemove?->name, 'user' => $user->name]) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$toggle('removePermissionModal')">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyUserPermission">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Remove Role Modal --}}
    <x-modals.modal wire:model="removeRoleModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('general.buttons.delete') }}</div></x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.users.delete_role_confirmation', ['role' => $roleToRemove?->name, 'user' => $user->name]) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$toggle('removeRoleModal')">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyUserRole">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>