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
                'url'   => route('admin.city-regions.render'),
                'label' => __('sidebar.city_regions'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.city_regions.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.city-regions.new') }}">
                    {{ __('admin.buttons.city_regions.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.internal_name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.display_name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.country') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.city') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.world_id') }}</x-tables.table-header>
                        <x-tables.table-header>Min X</x-tables.table-header>
                        <x-tables.table-header>Min Y</x-tables.table-header>
                        <x-tables.table-header>Min Z</x-tables.table-header>
                        <x-tables.table-header>Max X</x-tables.table-header>
                        <x-tables.table-header>Max Y</x-tables.table-header>
                        <x-tables.table-header>Max Z</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($cityRegions as $cityRegion)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $cityRegion->internal_name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->display_name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->country->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->city }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->world_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->min_x }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->min_y }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->min_z }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->max_x }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->max_y }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cityRegion->max_z }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.city-regions.edit', ['id' => $cityRegion->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCityRegion('{{ $cityRegion->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="4">
                                {{ __('admin.messages.city_regions.city_regions_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($cityRegions->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $cityRegions->links() }}
                            <x-tables.per-page-select wire:model.live="cityRegionsPerPage">
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

    {{-- Delete City Region Modal --}}
    <x-modals.modal wire:model="deleteCityRegionModal" :title="__('admin.titles.city_regions.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.city_regions.delete_confirmation', ['name' => $selectedCityRegion->internal_name ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteCityRegionModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyCityRegion">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
