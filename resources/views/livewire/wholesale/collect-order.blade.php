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
                'url' => route('wholesale.collect-order', ['wholesalerId' => $wholesaler->id, 'orderId' => $order->id]),
                'label' => __('wholesale.titles.collect_order'),
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
                    @if ($editOrder)
                        <span class="text-blue-500 text-sm">{{ __('wholesale.messages.editing_enabled') }}</span>
                    @else
                        <button
                            type="button"
                            wire:click="$toggle('editOrder')"
                            class="cursor-pointer"
                            >
                            <i 
                                class="fi fi-rr-pencil dark:text-white hover:text-gray-500"
                            ></i>
                        </button>
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
                            @if ($editOrder)
                                <button 
                                    type="button" 
                                    wire:click="decrement({{ $index }})" 
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $item['amount'] == 0 || empty($item['amount']) ? 'invisible' : '' }}"
                                    tabindex="-1"
                                >
                                    <i class="fi fi-rr-minus"></i>
                                </button>
                            @endif
    
                            <input 
                                type="number"
                                min="0"
                                max="{{ $item['max_amount'] }}"
                                wire:model.live="orderItems.{{ $index }}.amount"
                                wire:blur="calculatePrice({{ $index }})"
                                class="w-10 text-center text-sm font-medium text-gray-700 dark:text-white appearance-none bg-transparent border border-gray-400 dark:border-gray-600 rounded-md focus:outline-none focus:border-b focus:border-gray-400 hover:cursor-text transition-colors px-1 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                {{ !$editOrder ? 'disabled' : '' }}
                            >
                                
                            @if ($editOrder)
                                <button 
                                    type="button" 
                                    wire:click="increment({{ $index }})"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $item['amount'] >= $item['max_amount'] ? 'invisible' : '' }}"
                                    tabindex="-1"
                                >
                                    <i class="fi fi-rr-plus"></i>
                                </button>
                            @else
                                <x-forms.checkbox class="w-6 h-6"></x-forms.checkbox>
                            @endif
                        </div>
                        @if ($item['amount'] > $item['max_amount'])
                            <span class="flex justify-center text-xs text-red-500 mt-1">Max {{ $item['max_amount'] }}</span>
                        @endif
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
                {{ __('wholesale.titles.order_summary') }}
            </x-containers.title>

            <hr class="text-gray-400 dark:text-gray-600 mb-1.5">

            <div class="grid grid-cols-2">
                <div class="col-span-1 flex items-center">
                    <div class="text-md font-medium text-gray-700 dark:text-white">
                        {{ __('wholesale.labels.total') }}
                    </div>
                </div>

                <div class="col-span-1 flex items-center justify-end">
                    <div class="text-md font-medium text-gray-700 dark:text-white">
                        {{ $wholesaler->country->currency_symbol }}{{ number_format($total, 2) }}
                    </div>
                </div>

                <div class="col-span-2 flex items-center justify-center mt-5">
                    @if ($editOrder)
                        <x-buttons.primary-button class="w-full" wire:click="updateOrder()">
                            {{ __('wholesale.buttons.update_order') }}
                        </x-buttons.primary-button>
                    @else
                        <x-buttons.tertiary-button class="w-full" wire:click="collectOrder()">
                            {{ __('wholesale.buttons.collect_order') }}
                        </x-buttons.tertiary-button>
                    @endif
                </div>
            </div>
        </x-containers.main>
    </div>
</div>
