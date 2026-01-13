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
                    <x-forms.text-input label="{{ __('admin.labels.role_name') }}" wire:model="roleName" placeholder="{{ __('admin.placeholders.role_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
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
                    {{ __('admin.buttons.assign_permission') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.permission_name') }}</th>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($rolePermissions as $permission)
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $permission->name }}</td>
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.danger-action wire:click="removePermission('{{ $permission->uuid }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.role_no_permissions_assigned') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Assign Permission Modal --}}
    <x-modals.modal wire:model="assignPermissionModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.assign_permission') }}
            </div>
        </x-slot>
        <x-slot name="content">
            {{-- TODO: add mx-auto --}}
            <x-forms.select id="permissionSelect" wire:model="selectedPermission" label="{{ __('admin.labels.role_permissions') }}" required>
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
                {!! __('admin.messages.roles_modal_delete_permission_confirmation', ['permission' => $permissionToRemove?->name, 'role' => $role->name]) !!}
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
