<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold dark:text-white mb-4">{{ __('admin.users_overview_title') }}</h1>
        <div>
            <input x-ref="search" type="text" wire:model.live="searchUser" placeholder="{{ __('general.search_placeholder') }}" class="ml-4 px-3 py-2 border rounded" />
            <div @keyup.window.slash.prevent="$refs.search.focus()"></div>
        </div>
    </div>

    <div class="mt-6">
        <x-table-striped>
            <x-slot name="headers">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Selected Language</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Updated At</th>
                    <th></th>
                </tr>
            </x-slot>
            <x-slot name="rows">
                @forelse($users as $user)
                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->selectedLanguage->name ?? null }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->updated_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <span wire:click="editUser('{{ $user->uuid }}')" class="text-indigo-600 hover:text-indigo-900 ml-4 cursor-pointer">Edit</span>
                            <span wire:click="removeUser('{{ $user->uuid }}')" class="text-red-600 hover:text-red-900 ml-4 cursor-pointer">Delete</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ __('admin.users_no_records') }}
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="pagination">
                {{ $users->links() }}
            </x-slot>
        </x-table-striped>
    </div>

    {{-- Edit User Modal --}}
    <x-modal wire:model="editUserModal">
        <x-slot name="title">{{ __('admin.users_modal_edit_title') }}</x-slot>
        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label for="edit-user-name" class="block text-sm font-medium text-gray-700">{{ __('admin.users_modal_name_label') }}</label>
                    <input type="text" id="edit-user-name" wire:model="userName" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                    @error('userName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="edit-user-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" id="edit-user-email" wire:model="userEmail" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                    @error('userEmail') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="edit-user-language" class="block text-sm font-medium text-gray-700">{{ __('admin.users_modal_selected_language_label') }}</label>
                    <select id="edit-user-language" wire:model="selectedLanguage" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">{{ __('admin.users_modal_language_select_placeholder') }}</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedLanguage') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="updateUser" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                {{ __('general.save_button') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- Delete User Modal --}}
    <x-modal wire:model="deleteUserModal">
        <x-slot name="title">{{ __('admin.users_modal_delete_title') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.users_modal_delete_confirmation', ['name' => $selectedUser ? $selectedUser->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="destroyUser" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.delete_button') }}
            </button>
        </x-slot>
    </x-modal>
</div>
