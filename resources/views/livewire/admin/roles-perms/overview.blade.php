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
        ]" />
    </x-slot>

    {{-- Roles --}}
    <x-containers.main>
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.roles_overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchRole" wire:model.live="searchRole" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.roles-perms.role.new') }}">
                    {{ __('admin.buttons.role_create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.role_name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.role_permissions') }}</th>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($roles as $role)
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $role->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $role->permissions()->count() }}</td>
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.roles-perms.role.edit', ['uuid' => $role->uuid]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeRole('{{ $role->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.roles_no_records') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    {{ $roles->links() }}
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Permissions --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.permissions_overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar :id="'searchPermission'" wire:model.live="searchPermission" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.roles-perms.permission.new') }}">
                    {{ __('admin.buttons.permission_create') }}
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
                    @forelse($permissions as $permission)
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $permission->name }}</td>
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.roles-perms.permission.edit', ['uuid' => $permission->uuid]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePermission('{{ $permission->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.permissions_no_records') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    {{ $permissions->links() }}
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Delete Permission Modal --}}
    <x-modals.modal wire:model="deletePermissionModal">
        <x-slot name="title">{{ __('admin.titles.permission_delete') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.permissions_modal_delete_confirmation', ['name' => $selectedPermission ? $selectedPermission->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.buttons.cancel') }}
            </button>
            <button type="button" wire:click="destroyPermission" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.buttons.delete') }}
            </button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Role Modal --}}
    <x-modals.modal wire:model="deleteRoleModal">
        <x-slot name="title">{{ __('admin.titles.role_delete') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.roles_modal_delete_confirmation', ['name' => $selectedRole ? $selectedRole->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.buttons.cancel') }}
            </button>
            <button type="button" wire:click="destroyRole" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.buttons.delete') }}
            </button>
        </x-slot>
    </x-modals.modal>
</div>