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
        ]" />
    </x-slot>

    <div class="w-full flex justify-center">
        <x-containers.main class="md:w-[75%]">
            <x-containers.title>{{ __('company.titles.wholesale_orders') }}</x-containers.title>

            <div class="mt-6">
                <x-tables.table-striped>
                    <x-slot name="headers">
                        <tr>
                            <x-tables.table-header>#</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.order_id') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.total_amount') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.wholesale_status') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.status') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.completed_by') }}</x-tables.table-header>
                            <th></th>
                        </tr>
                    </x-slot>
                    <x-slot name="rows">
                        @forelse ($orders as $order)
                            <x-tables.table-row>
                                <x-tables.table-data>{{ $loop->iteration }}</x-tables.table-data>
                                <x-tables.table-data>{{ $order->id }}</x-tables.table-data>
                                <x-tables.table-data>{{ $order->order->amountOfItems() }}</x-tables.table-data>
                                <x-tables.table-data class="{{ $order->status === 'pending' ? 'text-red-500!' : ($order->status === 'collected' ? 'text-orange-400!' : 'text-green-400!') }}">
                                    {{ __('company.labels.' . $order->status) }}
                                </x-tables.table-data>
                                <x-tables.table-data class="{{ $order->completed ? 'text-green-400!' : 'text-red-500!' }}">
                                    {{ $order->completed ? __('company.labels.completed') : __('company.labels.pending') }}
                                </x-tables.table-data>
                                <x-tables.table-data>{{ $order->completed_by ? $order->completedBy->username : '-' }}</x-tables.table-data>
                                <x-tables.table-actions>
                                    <x-tables.primary-action href="{{ route('company.wholesale-orders.details.render', ['companyId' => $company->id, 'orderId' => $order->id]) }}">
                                        {{ __('general.buttons.view') }}
                                    </x-tables.primary-action>
                                </x-tables.table-actions>
                            </x-tables.table-row>
                        @empty
                            <x-tables.table-row>
                                <x-tables.empty-state colspan="7">
                                    {{ __('company.labels.no_orders') }}
                                </x-tables.empty-state>
                            </x-tables.table-row>
                        @endforelse
                    </x-slot>
                </x-tables.table-striped>
            </div>
        </x-containers.main>
    </div>
</div>
