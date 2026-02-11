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
                'url'   => route('company.stock.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.stock'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('company.titles.stock_overview') }}</x-containers.title>
            <x-buttons.primary-button href="">
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
                            <x-tables.table-data>{{ $item->stock->quantity }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action wire:click="updateItem('{{ $item->id }}')">
                                    {{ __('company.buttons.update') }}
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
