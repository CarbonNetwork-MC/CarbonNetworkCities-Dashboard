<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url'  => route('dashboard.render'),
                'label'=> '',
            ],
            [
                'url'   => route('admin.roles-perms.render'),
                'label' => __('sidebar.roles_perms'),
            ],
            [
                'url'   => route('admin.roles-perms.role.edit', ['uuid' => $role->uuid]),
                'label' => __('admin.titles.role_edit'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.titles.role_edit') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4">
                {{-- Role name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.role.name') }}" wire:model="roleName" placeholder="{{ __('admin.placeholders.roles.role_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateRole">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">
                {{ __('admin.titles.selected_permissions') }}
            </h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" />
                <x-buttons.primary-button size="sm" wire:click="$toggle('assignPermissionModal')">
                    {{ __('admin.buttons.roles.assign_permission') }}
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
                    @forelse($rolePermissions as $permission)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $permission->name }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removePermission('{{ $permission->uuid }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="2">
                                {{ __('admin.messages.permissions.no_permissions_assigned') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($rolePermissions->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $rolePermissions->links() }}
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Assign Permission Modal --}}
    <x-modals.modal wire:model="assignPermissionModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.roles.assign_permission') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <x-forms.select id="permissionSelect" wire:model="selectedPermission" label="{{ __('admin.labels.role.permissions') }}" required>
                <option value="">{{ __('general.placeholders.select_option') }}</option>
                @foreach($assignablePermissions as $permission)
                    <option value="{{ $permission->uuid }}">{{ $permission->name }}</option>
                @endforeach
            </x-forms.select>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$toggle('assignPermissionModal')">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.primary-button wire:click="addPermission">
                {{ __('admin.buttons.assign') }}
            </x-buttons.primary-button>
        </x-slot>
    </x-modals.modal>

    {{-- Remove Permission Modal --}}
    <x-modals.modal wire:model="removePermissionModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('general.buttons.delete') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <p class="text-center text-body">
                {!! __('admin.messages.permissions.delete_permission_confirmation', ['permission' => $permissionToRemove?->name, 'role' => $role->name]) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$toggle('removePermissionModal')">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPermission">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
