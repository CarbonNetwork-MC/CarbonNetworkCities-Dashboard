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
                'url'   => route('admin.wholesalers.render'),
                'label' => __('sidebar.wholesalers'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.wholesalers.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{-- route('admin.wholesalers.new') --}}">
                    {{ __('admin.titles.wholesalers.add') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.wholesalers.country') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.wholesalers.name') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($wholesalers as $wholesaler)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $wholesaler->country->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $wholesaler->name }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action size="sm" href="{{-- route('admin.wholesalers.edit', $wholesaler->id) --}}">
                                    {{ __('general.buttons.edit') }}
                                </x-tables.primary-action>
                                <x-tables.danger-action size="sm" wire:click="confirmDelete({{ $wholesaler->id }})">
                                    {{ __('general.buttons.delete') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="3">
                                {{ __('admin.labels.wholesalers.no_wholesalers') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($wholesalers->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $wholesalers->links() }}
                            <x-tables.per-page-select wire:model.live="wholesalersPerPage">
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
</div>
