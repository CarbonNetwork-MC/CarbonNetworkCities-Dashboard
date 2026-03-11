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
                'url'   => route('company.tips.render', ['companyId' => $company->id]),
                'label' => __('company.titles.tips'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('company.titles.tips') }}</x-containers.title>
            <x-buttons.primary-button href="{{ route('company.tips.new.render', ['companyId' => $company->id]) }}">
                {{ __('company.buttons.new_tip') }}
            </x-buttons.primary-button>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('company.labels.customer') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.amount') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.employee') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.date') }}</x-tables.table-header>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($tips as $tip)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $tip->customer->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->country->currency_symbol ?? '' }}{{ number_format($tip->amount, 2) }}</x-tables.table-data>
                            <x-tables.table-data>{{ $tip->employee->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ $tip->created_at->format('d-m-Y H:i') }}</x-tables.table-data>
                            <x-tables.table-actions>
                                @if ($hasPermission)
                                    <x-tables.danger-action wire:click="removeTip('{{ $tip->id }}')">
                                        {{ __('general.buttons.delete') }}
                                    </x-tables.danger-action>
                                @endif
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="5">
                                {{ __('company.messages.no_tips') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($tips->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $tips->links() }}
                            <x-tables.per-page-select wire:model.live="tipsPerPage">
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
