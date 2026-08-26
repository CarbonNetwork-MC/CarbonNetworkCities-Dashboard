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
                'url'   => route('company.archive.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.archive'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="w-full flex items-center gap-x-4">
            <x-containers.title>{{ __('sidebar.company.archive') }}</x-containers.title>
            <x-forms.select wrapper:class="w-16" wire:model.live="selectedWeek">
                @if (empty($weeks))
                    <option value="">-</option>
                @endif
                @foreach ($weeks as $week)
                    <option value="{{ $week }}">{{ $week }}</option>
                @endforeach
            </x-forms.select>
        </div>

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
                                <x-tables.primary-action href="{{ route('company.archive.details.render', ['companyId' => $company->id, 'saleId' => $sale->id]) }}">
                                    {{ __('general.buttons.view') }}
                                </x-tables.primary-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.table-data colspan="6" class="text-center text-gray-500">
                                {{ __('company.messages.no_sales_for_week') }}
                            </x-tables.table-data>
                        </x-tables.table-row>
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
                                <option value="100">100</option>
                            </x-tables.per-page-select>
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>
</div>
