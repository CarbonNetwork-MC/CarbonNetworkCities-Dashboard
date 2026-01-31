<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('admin.dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('admin.coc.render'),
                'label' => __('sidebar.coc'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.coc.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.coc.new') }}">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>#</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.coc.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.coc.description') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($cocTypes as $cocType)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $cocType->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cocType->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $cocType->description ?? __('general.labels.none') }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.coc.edit', ['id' => $cocType->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCoCType('{{ $cocType->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="4">
                                {{ __('admin.messages.coc.no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($cocTypes->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $cocTypes->links() }}
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

    {{-- Delete CoC Type Modal --}}
    <x-modals.modal wire:model="showDeleteModal">
        <x-slot name="title">{{ __('admin.titles.coc.delete') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.coc.delete_confirmation', ['name' => $typeToDelete->name ?? '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showDeleteCoCTypeModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="confirmRemoveCoCType">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
