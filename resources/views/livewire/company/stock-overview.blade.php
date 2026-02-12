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
                'url'   => route('company.choose.render'),
                'label' => __('sidebar.company.title'),
            ],
            [
                'url' => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => $company->name,
            ],
            [
                'url'   => route('company.stock.render', ['companyId' => $company->id]),
                'label' => __('company.titles.stock_overview'),
            ],
        ]" />
    </x-slot>

    <div class="flex justify-center">
        <x-containers.main class="w-[50%]">
            <div class="flex justify-between">
                <x-containers.title>{{ __('company.titles.stock_overview') }}</x-containers.title>
                <x-buttons.primary-button href="{{ route('company.stock.update.render', ['companyId' => $company->id]) }}">
                    {{ __('company.buttons.update_stock') }}
                </x-buttons.primary-button>
            </div>

            <div class="mt-6">
                <x-tables.table-striped>
                    <x-slot name="headers">
                        <tr>
                            <x-tables.table-header>{{ __('company.labels.item') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.quantity') }}</x-tables.table-header>
                            <th></th>
                        </tr>
                    </x-slot>
                    <x-slot name="rows">
                        @forelse ($company->items as $item)
                            <x-tables.table-row>
                                <x-tables.table-data>{{ $item->item->name }}</x-tables.table-data>
                                <x-tables.table-data>{{ $item->stock->quantity ?? 0 }}</x-tables.table-data>
                                <x-tables.table-actions>
                                    <x-tables.primary-action wire:click="openUpdateStockModal('{{ $item->id }}')">
                                        {{ __('general.buttons.update') }}
                                    </x-tables.primary-action>
                                </x-tables.table-actions>
                            </x-tables.table-row>
                        @empty
                            <x-tables.table-row>
                                <x-tables.empty-state :colspan="3">
                                    {{ __('company.messages.stock_no_records') }}
                                </x-tables.empty-state>
                            </x-tables.table-row>
                        @endforelse
                    </x-slot>
                    <x-slot name="pagination">

                    </x-slot>
                </x-tables.table-striped>
            </div>
        </x-containers.main>
    </div>

    <x-modals.modal wire:model="showUpdateStockModal">
        <x-slot name="title">
            <div class="flex justify-center">
                {{ __('company.titles.update_stock') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <div class="flex justify-center">
                <x-forms.number-input class="w-full" wire:model="selectedStockQuantity" />
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showUpdateStockModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.primary-button wire:click="saveStock">
                {{ __('general.buttons.confirm') }}
            </x-buttons.primary-button>
        </x-slot>
    </x-modals.modal>
</div>
