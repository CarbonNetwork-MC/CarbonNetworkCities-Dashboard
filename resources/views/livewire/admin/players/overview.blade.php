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
                'url'   => route('admin.players.render'),
                'label' => __('sidebar.players'),
            ],
        ]" />
    </x-slot>

    {{-- Players --}}
    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.players.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar x-ref="search" id="searchPlayer" wire:model.live="search" class="w-full" />
                <div @keyup.window.slash.prevent="$refs.search.focus()"></div>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.uuid') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.username') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.level') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.nationality') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.selected_language') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.playtime') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.last_login') }}</th>
                        <x-tables.table-header>{{ __('admin.labels.players.last_logout') }}</th>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($players as $player)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $player->uuid }}</x-tables.table-data>
                            <x-tables.table-data>{{ $player->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ $player->level }}</x-tables.table-data>
                            <x-tables.table-data>{{ $player->country->name ?? null }}</x-tables.table-data>
                            <x-tables.table-data>{{ $player->selectedLanguage->name ?? null }}</x-tables.table-data>
                            <x-tables.table-data>{{ \App\Helpers\TimeHelper::secondsToReadable($player->playtime) }}</x-tables.table-data>
                            <x-tables.table-data>{{ $player->last_login }}</x-tables.table-data>
                            <x-tables.table-data>{{ $player->last_logout }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.players.edit', ['uuid' => $player->uuid]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePlayer('{{ $player->uuid }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty

                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($players->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $players->links() }}
                            <x-tables.per-page-select wire:model.live="playersPerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </x-tables.per-page-select>
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Remove player modal --}}
    <x-modals.modal wire:model="showRemovePlayerModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.delete') }}</div></x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.players.delete_confirmation', ['name' => $this->playerToRemove->username ?? '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemovePlayerModal', false)">{{ __('general.buttons.cancel') }}</x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPlayer">{{ __('general.buttons.delete') }}</x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
