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
                'url'   => route('admin.plots.render'),
                'label' => 'Plots',
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.plots.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.plots.new') }}">
                    {{ __('admin.titles.plots.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>Plot ID</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.plots.description') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.city') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.country') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.company') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.owner') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.plots.members') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.plots.for_sale') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.plots.price') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.type') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($plots as $plot)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $plot->plot_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->description ? Str::limit($plot->description, 30, '...') : '' }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->city }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->country->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->company->name ?? '' }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->owner->username ?? '' }}</x-tables.table-data>
                            <x-tables.table-data>{{ count($plot->members) }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->for_sale ? __('general.yes') : __('general.no') }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{ $plot->price ? Number::currency($plot->price, $plot->country->currency) : '' }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($plot->type) }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.plots.edit', ['id' => $plot->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePlot('{{ $plot->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.table-data colspan="12" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.plots.plots_no_records') }}
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($plots->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $plots->links() }}
                            <x-tables.per-page-select wire:model.live="plotsPerPage">
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

    {{-- Delete Plot Modal --}}
    <x-modals.modal wire:model="deletePlotModal" :title="__('admin.titles.plots.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.plots.delete_confirmation', ['plot_id' => $selectedPlot ? $selectedPlot->plot_id : '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deletePlotModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPlot">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
