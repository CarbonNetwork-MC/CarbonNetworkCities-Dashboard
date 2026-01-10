<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold dark:text-white mb-4">{{ __('admin.permissions_overview_title') }}</h1>
        <div>
            <button wire:click="$toggle('createPermissionModal')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                {{ __('admin.permissions_create_button') }}
            </button>
            <input type="text" wire:model.live="searchPermission" placeholder="{{ __('general.search_placeholder') }}" class="ml-4 px-3 py-2 border rounded" />
        </div>
    </div>

    <div class="mt-6">
        <x-table-striped>
            <x-slot name="headers">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Permission Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Updated At</th>
                    <th></th>
                </tr>
            </x-slot>
            <x-slot name="rows">
                @forelse($permissions as $permission)
                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $permission->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $permission->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $permission->updated_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <span wire:click="editPermission('{{ $permission->uuid }}')" class="text-indigo-600 hover:text-indigo-900 cursor-pointer">Edit</span>
                            <span wire:click="removePermission('{{ $permission->uuid }}')" class="text-red-600 hover:text-red-900 ml-4 cursor-pointer">Delete</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ __('admin.permissions_no_records') }}
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="pagination">
                {{ $permissions->links() }}
            </x-slot>
        </x-table-striped>
    </div>

    {{-- Create Permission Modal --}}
    <x-modal wire:model="createPermissionModal">
        <x-slot name="title">{{ __('admin.permissions_modal_create_title') }}</x-slot>
        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label for="permission-name" class="block text-sm font-medium text-gray-700">{{ __('admin.permissions_modal_name_label') }}</label>
                    <input type="text" id="permission-name" wire:model="permissionName" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                    @error('permissionName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="savePermission" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                {{ __('general.save_button') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- Edit Permission Modal --}}
    <x-modal wire:model="editPermissionModal">
        <x-slot name="title">{{ __('admin.permissions_modal_edit_title') }}</x-slot>
        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label for="edit-permission-name" class="block text-sm font-medium text-gray-700">{{ __('admin.permissions_modal_name_label') }}</label>
                    <input type="text" id="edit-permission-name" wire:model="permissionName" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                    @error('permissionName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="updatePermission" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                {{ __('general.save_button') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- Delete Permission Modal --}}
    <x-modal wire:model="deletePermissionModal">
        <x-slot name="title">{{ __('admin.permissions_modal_delete_title') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.permissions_modal_delete_confirmation', ['name' => $selectedPermission ? $selectedPermission->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="destroyPermission" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.delete_button') }}
            </button>
        </x-slot>
    </x-modal>
</div>
