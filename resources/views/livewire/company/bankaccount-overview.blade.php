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
                'url'   => route('company.bank-accounts.render', ['companyId' => $company->id]),
                'label' => __('company.titles.choose_bank_account'),
            ],
            [
                'url'   => route('company.bank-account.render', ['companyId' => $company->id, 'bankAccountId' => $bankAccount->id]),
                'label' => __('company.labels.account') . ' #' . $bankAccount->id,
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('company.labels.account') }} #{{ $bankAccount->id }}</x-containers.title>

        <div class="grid grid-cols-2 gap-x-4 w-[25%] mt-2">
            <div class="col-span-1">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">{{ __('company.labels.balance') }}</h3>
            </div>
            <div class="col-span-1">
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $currencySymbol }}{{ number_format($bankAccount->balance, 2) }}</p>
            </div>

            <div class="col-span-1">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">{{ __('company.labels.is_main') }}</h3>
            </div>
            <div class="col-span-1">
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $bankAccount->is_main ? __('general.yes') : __('general.no') }}</p>
            </div>
        </div>
    </x-containers.main>

    <x-containers.main class="mt-4">
        <x-containers.title>{{ __('company.titles.transactions') }}</x-containers.title>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('company.labels.date') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.amount') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.type') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.counterparty') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.description') }}</x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($transactions as $transaction)
                        @php
                            $isOutgoing = $transaction->from_company_id === $bankAccount->id;
                            if ($isOutgoing) {
                                $counterParty = $transaction->to_company_name ?? $transaction->to_player_name ?? '-';
                                $counterPartyType = $transaction->to_company_id ? __('company.labels.company') : ($transaction->to_personal_id ? __('company.labels.player') : __('company.labels.unknown'));
                            } else {
                                $counterParty = $transaction->from_company_name ?? $transaction->from_player_name ?? '-';
                                $counterPartyType = $transaction->from_company_id ? __('company.labels.company') : ($transaction->from_personal_id ? __('company.labels.player') : __('company.labels.unknown'));
                            }
                            $type = $transaction->transaction_type === 'withdraw' ? __('company.labels.withdrawal') : ($transaction->transaction_type === 'deposit' ? __('company.labels.deposit') : __('company.labels.transfer'));
                        @endphp

                        <x-tables.table-row>
                            <x-tables.table-data>{{ $transaction->created_at->format('d-m-Y H:i:s') }}</x-tables.table-data>
                            <x-tables.table-data class="{{ $isOutgoing ? 'text-red-500!' : 'text-green-400!' }}">{{ $isOutgoing ? '-' : '+' }} {{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}</x-tables.table-data>
                            <x-tables.table-data>{{ $type }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{ $counterParty }}
                                @if ($counterParty !== '-')
                                    <span class="text-sm text-gray-400 ms-1">({{ $counterPartyType }})</span>
                                @endif
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $transaction->description ?? '-' }}</x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="5">
                                {{ __('company.messages.no_transactions') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">

                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>
</div>
