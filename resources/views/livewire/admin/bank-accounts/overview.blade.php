<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url'  => route('admin.dashboard.render'),
                'label'=> '',
            ],
            [
                'url'   => route('admin.bank-accounts.render'),
                'label' => __('sidebar.bank_accounts'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.bank_accounts.company.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="searchCompany" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.bank-accounts.company.new') }}">
                    {{ __('admin.titles.bank_accounts.company.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.pin_consoles.company') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_balance') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_is_main') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_currency') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($companyBankAccounts as $account)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $account->company->name }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{ Number::currency($account->balance, $account->currency) }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $account->is_main ? __('general.yes') : __('general.no') }}</x-tables.table-data>
                            <x-tables.table-data>{{ $account->currency }}</x-tables.table-data>                          
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.bank-accounts.company.edit', ['id' => $account->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCompanyBankAccount('{{ $account->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.table-data colspan="5">
                                {{ __('admin.messages.bank_accounts.company_bank_accounts_no_records') }}
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($companyBankAccounts->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $companyBankAccounts->links() }}
                            <x-tables.per-page-select wire:model.live="companyBankAccountsPerPage">
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

    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.bank_accounts.personal.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="searchPersonal" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.bank-accounts.personal.new') }}">
                    {{ __('admin.titles.bank_accounts.personal.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.player_username') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_balance') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.bank_accounts.account_type') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_currency') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($personalBankAccounts as $account)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $account->player->username }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{ Number::currency($account->balance, $account->currency) }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($account->type) }}</x-tables.table-data>
                            <x-tables.table-data>{{ $account->currency }}</x-tables.table-data>                          
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.bank-accounts.personal.edit', ['id' => $account->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePersonalBankAccount('{{ $account->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.table-data colspan="5">
                                {{ __('admin.messages.bank_accounts.personal_bank_accounts_no_records') }}
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($personalBankAccounts->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $personalBankAccounts->links() }}
                            <x-tables.per-page-select wire:model.live="personalBankAccountsPerPage">
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

    {{-- Delete Company Bank Account Modal --}}
    <x-modals.modal wire:model="deleteCompanyBankAccountModal" :title="__('admin.titles.bank_accounts.company.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.bank_accounts.company_bank_account_delete_confirmation', ['id' => $selectedCompanyBankAccount ? $selectedCompanyBankAccount->id : '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteCompanyBankAccountModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyCompanyBankAccount">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Personal Bank Account Modal --}}
    <x-modals.modal wire:model="deletePersonalBankAccountModal" :title="__('admin.titles.bank_accounts.personal.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.bank_accounts.personal_bank_account_delete_confirmation', ['id' => $selectedPersonalBankAccount ? $selectedPersonalBankAccount->id : '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deletePersonalBankAccountModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPersonalBankAccount">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
