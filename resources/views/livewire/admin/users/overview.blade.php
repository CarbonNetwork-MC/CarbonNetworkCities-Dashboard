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
                        <x-tables.table-header>{{ __('general.labels.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('general.labels.email') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.users.selected_language') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('general.labels.created_at') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('general.labels.updated_at') }}</x-tables.table-header>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($users as $user)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $user->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $user->email }}</x-tables.table-data>
                            <x-tables.table-data>{{ $user->selectedLanguage->name ?? null }}</x-tables.table-data>
                            <x-tables.table-data>{{ $user->created_at->format('Y-m-d H:i:s') }}</x-tables.table-data>
                            <x-tables.table-data>{{ $user->updated_at->format('Y-m-d H:i:s') }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.users.edit', ['uuid' => $user->uuid]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                @if ($user->accountLink)
                                    <x-tables.danger-action wire:click="unlinkAccount('{{ $user->uuid }}')">{{ __('admin.buttons.users.unlink') }}</x-tables.danger-action>
                                @endif
                                <x-tables.danger-action wire:click="removeUser('{{ $user->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="6" >
                                {{ __('admin.messages.users.no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
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
                {{ __('general.buttons.cancel') }}
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
                {{ __('general.buttons.cancel') }}
            </button>
            <button type="button" wire:click="destroyUser" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.button.delete') }}
            </button>
        </x-slot>
    </x-modals.modal>
</div>
