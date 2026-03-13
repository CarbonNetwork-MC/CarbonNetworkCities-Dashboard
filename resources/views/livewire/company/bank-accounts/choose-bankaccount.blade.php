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
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('company.titles.choose_bank_account') }}</x-containers.title>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>#</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.balance') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('company.labels.is_main') }}</x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($bankAccounts as $bankAccount)
                        <x-tables.table-row wire:click="selectBankAccount('{{ $bankAccount->id }}')" class="cursor-pointer">
                            <x-tables.table-data>{{ $loop->iteration }}</x-tables.table-data>
                            <x-tables.table-data>{{ $bankAccount->country->currency_symbol ?? '' }}{{ number_format($bankAccount->balance, 2) }}</x-tables.table-data>
                            <x-tables.table-data>
                                @if ($bankAccount->is_main)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.yes') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.no') }}</span>
                                @endif
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="3" class="text-center py-4">
                                {{ __('company.messages.no_bank_accounts') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>
</div>
