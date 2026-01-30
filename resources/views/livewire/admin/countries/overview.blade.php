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
                'url'   => route('admin.countries.render'),
                'label' => __('sidebar.countries'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.countries.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.countries.new') }}">
                    {{ __('admin.buttons.countries.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.countries.name') }}</x-tables.table-header>
                        <x-tables.table-header>ISO</x-tables.table-header>
                        <x-tables.table-header>Code</x-tables.table-header>
                        <x-tables.table-header>HeadDB ID</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.countries.currency') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.countries.currency_symbol') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.countries.currency_before_amount') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($countries as $country)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $country->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $country->iso }}</x-tables.table-data>
                            <x-tables.table-data>{{ $country->code }}</x-tables.table-data>
                            <x-tables.table-data>{{ $country->headdb_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $country->currency }}</x-tables.table-data>
                            <x-tables.table-data>{{ $country->currency_symbol }}</x-tables.table-data>
                            <x-tables.table-data>{{ $country->currency_before_amount ? __('general.yes') : __('general.no') }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.countries.edit', ['id' => $country->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCountry('{{ $country->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.table-data colspan="4">
                                {{ __('admin.messages.countries.countries_no_records') }}
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($countries->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $countries->links() }}
                            <x-tables.per-page-select wire:model.live="countriesPerPage">
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

    {{-- Delete Country Modal --}}
    <x-modals.modal wire:model="deleteCountryModal" :title="__('admin.titles.countries.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.countries.delete_confirmation', ['name' => $selectedCountry->name ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteCountryModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyCountry">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
