<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('wholesale.order-overview'),
                'label' => __('wholesale.titles.orders_overview'),
            ]
        ]" />
    </x-slot>

    {{-- Orders --}}
    <x-containers.main class="mb-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('wholesale.titles.orders_to_collect') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchOrders" wire:model.live="searchOrders" />
            </div>
        </div>

        <div class="mt-6" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr class="select-none">
                        <x-tables.table-header>{{ __('admin.labels.companies.company_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.company') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.total') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.amount_of_items') }}</x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($orders as $order)
                        <x-tables.table-row wire:click="selectOrder({{ $order->id }})" class="cursor-pointer">
                            <x-tables.table-data>{{ $order->company->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->company->name }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{-- TODO: currency based on wholesale currency --}}
                                {{ Number::currency($order->total) }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $order->amountOfItems() }}</x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row class="select-none">
                            <x-tables.empty-state colspan="6">
                                {{ __('wholesale.messages.no_orders') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($orders->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $orders->links() }}
                            <x-tables.per-page-select wire:model.live="ordersPerPage">
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

    {{-- Collected Orders --}}
    <x-containers.main class="mb-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('wholesale.titles.orders_to_complete') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchCollectedOrders" wire:model.live="searchCollectedOrders" />
            </div>
        </div>

        <div class="mt-6" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr class="select-none">
                        <x-tables.table-header>{{ __('admin.labels.companies.company_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.company') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.total') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.amount_of_items') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.collected_by') }}</x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($collectedOrders as $order)
                        <x-tables.table-row wire:click="selectOrder({{ $order->id }})" class="cursor-pointer">
                            <x-tables.table-data>{{ $order->company->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->company->name }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{-- TODO: currency based on wholesale currency --}}
                                {{ Number::currency($order->total) }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $order->amountOfItems() }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->collectedBy->username ?? '-' }}</x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row class="select-none">
                            <x-tables.empty-state colspan="6">
                                {{ __('wholesale.messages.no_collected_orders') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($collectedOrders->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $collectedOrders->links() }}
                            <x-tables.per-page-select wire:model.live="collectedOrdersPerPage">
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

    {{-- Completed Orders --}}
    <x-containers.main x-data="{open: false}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('wholesale.titles.completed_orders') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchCompletedOrders" wire:model.live="searchCompletedOrders" />
            </div>
        </div>

        <div class="mt-6" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr class="select-none">
                        <x-tables.table-header>{{ __('admin.labels.companies.company_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.company') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.total') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.amount_of_items') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.collected_by') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.labels.completed_by') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('wholesale.titles.customer') }}</x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($completedOrders as $order)
                        <x-tables.table-row class="cursor-pointer">
                            <x-tables.table-data>{{ $order->company->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->company->name }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{-- TODO: currency based on wholesale currency --}}
                                {{ Number::currency($order->total) }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $order->amountOfItems() }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->collectedBy->username ?? '-' }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->completedBy->username ?? '-' }}</x-tables.table-data>
                            <x-tables.table-data>{{ $order->customer->username }}</x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row class="select-none">
                            <x-tables.empty-state colspan="6">
                                {{ __('wholesale.messages.no_completed_orders') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($completedOrders->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $completedOrders->links() }}
                            <x-tables.per-page-select wire:model.live="completedOrdersPerPage">
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
