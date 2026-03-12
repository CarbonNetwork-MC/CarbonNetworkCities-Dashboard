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
                'url'   => route('company.salaries.render', ['companyId' => $company->id]),
                'label' => __('company.titles.salaries'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="w-full flex items-center gap-x-4">
            <x-containers.title>{{ __('company.titles.salaries') }}</x-containers.title>
            <x-forms.select wrapper:class="w-16" wire:model.live="selectedWeek">
                @foreach ($weeks as $week)
                    <option value="{{ $week }}">{{ $week }}</option>
                @endforeach
            </x-forms.select>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('company.labels.employee') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.amount') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.status') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.paid_at') }}</x-tables.table-header>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($salaries as $salary)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $salary->player->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->country->currency_symbol }}{{ number_format($salary->amount, 2) }}</x-tables.table-data>
                            <x-tables.table-data>
                                @switch ($salary->status)
                                    @case('unpaid')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('company.placeholders.awaiting_payment') }}</span>
                                        @break
                                    @case('completed')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('company.placeholders.paid') }}</span>
                                        @break
                                    @case('transfered')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('company.placeholders.transfered') }}</span>
                                        @break
                                @endswitch
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $salary->paid_at ? $salary->paid_at->format('Y-m-d H:i') : '-' }}</x-tables.table-data>
                            <x-tables.table-actions>
                                @if ($salary->amount > 0)
                                    @if ($salary->status !== 'unpaid')
                                        <x-tables.danger-action wire:click="markUnpaid('{{ $salary->id }}')">
                                            {{ __('company.buttons.mark_unpaid') }}
                                        </x-tables.danger-action>
                                    @elseif ($salary->status === 'unpaid')
                                        <x-tables.secondary-action wire:click="markPaid('{{ $salary->id }}')">
                                            {{ __('company.buttons.mark_paid') }}
                                        </x-tables.secondary-action>
                                    @endif
                                    @if ($salary->status !== 'transfered')
                                        <x-tables.primary-action wire:click="transferSalary('{{ $salary->id }}')">
                                            {{ __('company.buttons.transfer_salary') }}
                                        </x-tables.primary-action>
                                    @endif
                                @endif
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="5">
                                {{ __('company.messages.no_salaries_for_week') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>
</div>
