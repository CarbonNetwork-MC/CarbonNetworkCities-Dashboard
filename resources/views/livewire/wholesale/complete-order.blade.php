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
                'url' => route('wholesale.order-overview', ['wholesalerId' => $wholesaler->id]),
                'label' => __('wholesale.titles.orders_overview'),
            ],
            [
                'icon' => '',
                'url' => route('wholesale.complete-order', ['wholesalerId' => $wholesaler->id, 'orderId' => $order->id]),
                'label' => __('wholesale.titles.complete_order'),
            ]
        ]" />
    </x-slot>

    <div class="flex items-center flex-col">
        <x-containers.main class="grid grid-cols-1 gap-2 w-[50%]">
            <div class="flex justify-between">
                <x-containers.title class="mb-2">
                    {{ __('wholesale.titles.order_overview') }}
                </x-containers.title>

                <div class="flex items-center gap-2">
                    @if (auth()->user()->hasPermissionTo('delete_wholesale_orders'))
                        <x-buttons.danger-button class="w-full" wire:click="removeOrder()">
                            {{ __('wholesale.titles.delete_order') }}
                        </x-buttons.danger-button>
                    @endif
                </div>
            </div>
                
            @foreach ($orderItems as $index => $item)
                <div class="grid grid-cols-3">
                    <div class="col-span-1 flex items-center">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $item['name'] }}
                        </div>
                    </div>
                    
                    <div class="col-span-1">
                        <div class="flex items-center justify-center gap-2">    
                            <input 
                                type="number"
                                wire:model.live="orderItems.{{ $index }}.amount"
                                class="w-10 text-center text-sm font-medium text-gray-700 dark:text-white appearance-none bg-transparent border border-gray-400 dark:border-gray-600 rounded-md focus:outline-none focus:border-b focus:border-gray-400 hover:cursor-text transition-colors px-1 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                disabled
                            >
                        </div>
                    </div>

                    <div class="col-span-1 flex items-center justify-end">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $wholesaler->country->currency_symbol }}{{ number_format($item['total'], 2) }}
                        </div>
                    </div>
                </div>
                <hr class="text-gray-400 dark:text-gray-600">
            @endforeach
        </x-containers.main>

        <x-containers.main class="w-[50%] mt-4">
            <x-containers.title marginBottom="1">
                {{ __('wholesale.titles.customer') }}
            </x-containers.title>

            <livewire:async-select
                :options="$players->map(fn($player) => ['label' => $player['username'], 'value' => $player['uuid']])"
                wire:model.live="customerUuid"
                :min-search-length="2"
            />
        </x-containers.main>

        <x-containers.main class="w-[50%] mt-4">
            <x-containers.title marginBottom="1">
                {{ __('wholesale.titles.order_summary') }}
            </x-containers.title>

            <hr class="text-gray-400 dark:text-gray-600 mb-1.5">

            <div class="grid grid-cols-2 gap-x-4">
                <div class="col-span-1 flex items-center">
                    <div class="text-md font-medium text-gray-700 dark:text-white">
                        {{ __('wholesale.labels.total') }}
                    </div>
                </div>

                <div class="col-span-1 flex items-center justify-end">
                    <div class="text-md font-medium text-gray-700 dark:text-white">
                        {{ $wholesaler->country->currency_symbol }}{{ number_format($order->total, 2) }}
                    </div>
                </div>

                <div class="col-span-1 flex items-center justify-center mt-5">
                    <x-buttons.secondary-button class="w-full" wire:click="undoCollect">
                        {{ __('wholesale.buttons.undo_collect_order') }}
                    </x-buttons.secondary-button>
                </div>

                <div class="col-span-1 flex items-center justify-center mt-5">
                    <x-buttons.primary-button class="w-full" wire:click="completeOrder">
                        {{ __('wholesale.buttons.complete_order') }}
                    </x-buttons.primary-button>
                </div>
            </div>
        </x-containers.main>
    </div>

    {{-- Delete Order Modal --}}
    <x-modals.modal wire:model="deleteOrderModal" :title="__('wholesale.titles.delete_order')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {{ __('wholesale.messages.delete_order_confirmation') }}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteOrderModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyOrder">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Undo Collect Modal --}}
    <x-modals.modal wire:model="undoCollectModal" :title="__('wholesale.titles.undo_collect_order')">
        <x-slot name="content">
            <div class="flex justify-center">
                <p class="text-gray-700 dark:text-gray-300">
                    {{ __('wholesale.messages.undo_collect_confirmation') }}
                </p>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('undoCollectModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="undoCollectOrder">
                {{ __('wholesale.buttons.undo_collect_order') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
