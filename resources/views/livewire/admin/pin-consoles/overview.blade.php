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
                'url'   => route('admin.pin-consoles.render'),
                'label' => __('sidebar.pin_consoles'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.pin_consoles.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.pin-consoles.new') }}">
                    {{ __('admin.titles.pin_consoles.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.companies.pin_console_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.pin_consoles.company') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.pin_console_account') }}</x-tables.table-header>
                        <x-tables.table-header>X</x-tables.table-header>
                        <x-tables.table-header>Y</x-tables.table-header>
                        <x-tables.table-header>Z</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.city') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.country') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.world_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.pin_console_is_active') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($pinConsoles as $pinConsole)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $pinConsole->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->company->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->account_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->x }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->y }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->z }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->city }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->country->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->world_id }}</x-tables.table-data>
                            <x-tables.table-data>
                                @if ($pinConsole->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.yes') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.no') }}</span>
                                @endif
                            </x-tables.table-data>                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.pin-consoles.edit', ['id' => $pinConsole->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePinConsole('{{ $pinConsole->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.table-data colspan="4">
                                {{ __('admin.messages.pin_consoles.pin_consoles_no_records') }}
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($pinConsoles->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $pinConsoles->links() }}
                            <x-tables.per-page-select wire:model.live="pinConsolesPerPage">
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

    {{-- Delete PIN Console Modal --}}
    <x-modals.modal wire:model="deletePinConsoleModal" :title="__('admin.titles.pin_consoles.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {{ __('admin.messages.pin_consoles.delete_confirmation') }}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deletePinConsoleModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPinConsole">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
