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
                'url'   => route('company.sales.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.sales'),
            ],
        ]" />
    </x-slot>

    <div class="flex gap-x-4">
        <div class="w-full md:w-[75%]">
            <x-containers.main>
                <x-containers.title>{{ __('company.titles.sales') }}</x-containers.title>

                <div class="mt-6">
                    <x-tables.table-striped>
                        <x-slot name="headers">
                            <tr>
                                <x-tables.table-header>{{ __('company.labels.date') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('company.labels.customer') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('company.labels.total') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('company.labels.price') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('company.labels.employee') }}</x-tables.table-header>
                                <th class="w-24"></th>
                            </tr>
                        </x-slot>
                        <x-slot name="rows">
                            @forelse ($sales as $sale)
                                <x-tables.table-row>
                                    <x-tables.table-data>{{ $sale->created_at->format('d-m-Y H:i') }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $sale->customer->username }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $sale->quantity }} {{ __('company.labels.units') }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $company->country->currency_symbol ?? '' }}{{ number_format($sale->total_revenue, 2) }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $sale->employee->username }}</x-tables.table-data>
                                    <x-tables.table-actions>
                                        <x-tables.primary-action href="">
                                            {{ __('general.buttons.view') }}
                                        </x-tables.primary-action>
                                        @if ($hasPermission)
                                            <x-tables.danger-action href="">
                                                {{ __('general.buttons.delete') }}
                                            </x-tables.danger-action>
                                        @endif
                                    </x-tables.table-actions>
                                </x-tables.table-row>
                            @empty

                            @endforelse
                        </x-slot>
                        <x-slot name="pagination">
                            @if ($sales->hasPages())
                                <div class="w-full flex items-center gap-x-4 mt-4">
                                    {{ $sales->links() }}
                                    <x-tables.per-page-select wire:model.live="salesPerPage">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </x-tables.per-page-select>
                                </div>
                            @endif
                        </x-slot>
                    </x-tables.table-striped>
                </div>
            </x-containers.main>
        </div>
        <div class="w-full md:w-[25%]">
            <x-containers.main>
                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-2">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.product') }}
                        </div>
                    </div>
                    <div class="col-span-1 flex justify-center">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.price') }}
                        </div>
                    </div>
                    <div class="col-span-1 flex justify-end">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.stock') }}
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    @forelse ($products as $product)
                        <div class="grid grid-cols-4 gap-2 border-b border-gray-400 dark:border-gray-600 mb-2 pb-2">
                            <div class="col-span-2">
                                <div class="text-md font-medium text-gray-700 dark:text-white">
                                    {{ $product->item->name }}
                                </div>
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <div class="text-md text-gray-700 dark:text-white">
                                    {{ $company->country->currency_symbol ?? '' }}{{ $product->price }}
                                </div>
                            </div>
                            <div class="col-span-1 flex justify-end">
                                <div 
                                    class="text-md {{ $product->stock->quantity > $product->stock->warning_threshold ? 'text-green-400' 
                                    : ($product->stock->quantity > $product->stock->critical_threshold ? 'text-yellow-400' : 'text-red-400') }}"
                                >
                                    {{ $product->stock->quantity }}
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
            </x-containers.main>
        </div>
    </div>
</div>
