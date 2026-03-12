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
                'url'   => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => $company->name,
            ],
            [
                'url'   => route('company.wholesale-orders.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.wholesale_orders'),
            ],
            [
                'url'   => route('company.wholesale-orders.details.render', ['companyId' => $company->id, 'orderId' => $order->id]),
                'label' => __('company.titles.order_details'),
            ]
        ]" />
    </x-slot>

    <div class="w-full flex flex-col items-center">
        <x-containers.main class="w-[50%]">
            <x-containers.title>{{ __('company.titles.order_details') }} - {{ __('company.labels.order') }} #{{ $order->id }}</x-containers.title>

            <div class="mt-4">
                @forelse ($order->order->items as $item)
                    <div class="flex justify-between px-4">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $item->item->name }}
                        </div>

                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $item->amount }} {{ __('company.labels.units') }}
                        </div>
                    </div>

                    <x-containers.divider height="0.5" margin="my-4" />
                @empty
                    <div class="flex justify-center">
                        <p>{{ __('company.messages.no_order_items') }}</p>
                    </div>
                @endforelse
            </div>
        </x-containers.main>
        
        <x-containers.main class="w-[50%] mt-4">
            <div class="flex justify-between px-4">
                <p class="text-black dark:text-white font-bold">
                    {{ __('company.labels.wholesale_status') }}:
                </p>
                <span class="{{ $order->status === 'pending' ? 'text-red-500' : ($order->status === 'collected' ? 'text-orange-400' : 'text-green-400') }}">{{ __('company.labels.' . $order->status) }}</span>
            </div>

            <div class="flex justify-between px-4">
                <p class="text-black dark:text-white font-bold">
                    {{ __('company.labels.status') }}:
                </p>
                <span class="{{ $order->completed ? 'text-green-400' : 'text-red-500' }}">{{ $order->completed ? __('company.labels.completed') : __('company.labels.pending') }}</span>
            </div>

            <div class="flex justify-between px-4">
                <p class="text-black dark:text-white font-bold">
                    {{ __('company.labels.inventory_updated') }}
                </p>
                <span class="{{ $order->stock_updated ? 'text-green-400' : 'text-red-500' }}">{{ $order->stock_updated ? __('general.yes') : __('general.no') }}</span>
            </div>

            <div class="flex justify-between px-4">
                <p class="text-black dark:text-white font-bold">
                    {{ __('company.labels.completed_by') }}:
                </p>
                <span class="text-black dark:text-white">{{ $order->completedBy?->name ?? 'N/A' }}</span>
            </div>
        </x-containers.main>

        @if ($order->status !== 'pending')
            <x-containers.main class="w-[50%] mt-4">
                <div class="grid grid-cols-2 gap-x-4">
                    @if ($order->completed)
                        <div class="col-span-2">
                            <x-buttons.danger-button wire:click="markAsUncompleted" class="w-full">
                                {{ __('company.buttons.mark_as_uncompleted') }}
                            </x-buttons.danger-button>
                        </div>
                    @else
                        <div class="col-span-1">
                            <x-buttons.tertiary-button wire:click="markAsCompleted" class="w-full">
                                {{ __('company.buttons.mark_as_completed') }}
                            </x-buttons.tertiary-button>
                        </div>
                        <div class="col-span-1">
                            <x-buttons.primary-button wire:click="markAsCompletedAndUpdateStock" class="w-full">
                                {{ __('company.buttons.mark_as_completed_and_update_inventory') }}
                            </x-buttons.primary-button>
                        </div>
                    @endif
                </div>
            </x-containers.main>
        @endif
    </div>
</div>
