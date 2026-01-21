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
                'url'   => route('admin.users.render'),
                'label' => __('sidebar.users'),
            ],
        ]" />
    </x-slot>

    {{-- Users --}}
    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.users.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar x-ref="search" id="searchUser" wire:model.live="searchUser" class="w-full" />
                <div @keyup.window.slash.prevent="$refs.search.focus()"></div>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('general.labels.name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('general.labels.email') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.users.selected_language') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('general.labels.created_at') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('general.labels.updated_at') }}</th>
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
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.users.edit', ['uuid' => $user->uuid]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                @if ($user->accountLink)
                                    <x-tables.danger-action wire:click="unlinkAccount('{{ $user->uuid }}')">{{ __('admin.buttons.users.unlink') }}</x-tables.primary-action>
                                @endif
                                <x-tables.danger-action wire:click="removeUser('{{ $user->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.users_no_records') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    {{ $users->links() }}
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Unlink Account Modal --}}
    <x-modals.modal wire:model="unlinkModal">
        <x-slot name="title">{{ __('admin.titles.users.unlink') }}</x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.users.unlink_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="unlink" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('admin.buttons.buttons.users.unlink_account') }}
            </button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete User Modal --}}
    <x-modals.modal wire:model="deleteUserModal">
        <x-slot name="title">{{ __('admin.titles.users.delete') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.users.delete_confirmation', ['name' => $selectedUser ? $selectedUser->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.cancel_button') }}
            </button>
            <button type="button" wire:click="destroyUser" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.delete_button') }}
            </button>
        </x-slot>
    </x-modals.modal>
</div>
