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
            <x-containers.title>{{ __('admin.titles.wholesale_items.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.wholesale-items.new') }}">
                    {{ __('admin.titles.wholesale_items.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.wholesale_items.item') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.wholesale_items.price') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.wholesale_items.max_amount') }}</x-tables.table-header>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($items as $item)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $item->item->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->price }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->max_amount }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.wholesale-items.edit', ['id' => $item->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeItem('{{ $item->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="4">
                                {{ __('admin.messages.wholesale_items.no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($items->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $items->links() }}
                            <x-tables.per-page-select wire:model.live="itemsPerPage">
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
