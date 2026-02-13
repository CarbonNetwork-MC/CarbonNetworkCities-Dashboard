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

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('company.titles.stock_overview') }}</x-containers.title>
            @if ($hasPermission)
                <x-buttons.primary-button href="{{ route('company.stock.update.render', ['companyId' => $company->id]) }}">
                    {{ __('company.buttons.update_stock') }}
                </x-buttons.primary-button>
            @endif
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('company.labels.item') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.quantity') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.stock_level') }}</x-tables.table-header>
                        @if ($hasPermission)
                            <x-tables.table-header>{{ __('company.labels.preferred_stock_level') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.warning_threshold') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.critical_threshold') }}</x-tables.table-header>
                            <th></th>
                        @endif
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($company->items as $item)
                        @php
                            $percentage = min(100, ($item->stock->quantity / max(1, $item->stock->preferred_stock_level)) * 100);

                            $color = $percentage > $item->stock->warning_threshold
                                ? 'bg-emerald-500'
                                : ($percentage > $item->stock->critical_threshold
                                    ? 'bg-yellow-500'
                                    : 'bg-red-500');
                        @endphp
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $item->item->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->stock->quantity ?? 0 }}</x-tables.table-data>
                            <x-tables.table-data>
                                <div class="w-full bg-gray-200 dark:bg-gray-300 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $color }}"
                                        style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </x-tables.table-data>
                            @if ($hasPermission)
                                <x-tables.table-data>{{ $item->stock->preferred_stock_level }}</x-tables.table-data>
                                <x-tables.table-data>{{ $item->stock->warning_threshold }}</x-tables.table-data>
                                <x-tables.table-data>{{ $item->stock->critical_threshold }}</x-tables.table-data>
                                <x-tables.table-actions>
                                    <x-tables.primary-action href="{{ route('company.stock.edit.render', ['companyId' => $company->id, 'itemId' => $item->stock->id]) }}">
                                        {{ __('general.buttons.edit') }}
                                    </x-tables.primary-action>
                                    <x-tables.primary-action wire:click="openUpdateStockModal('{{ $item->id }}')">
                                        {{ __('company.buttons.update_stock') }}
                                    </x-tables.primary-action>
                                </x-tables.table-actions>
                            @endif
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="$hasPermission ? 7 : 3">
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
