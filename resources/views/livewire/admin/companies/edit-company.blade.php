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
                'url'   => route('admin.companies.render'),
                'label' => __('sidebar.companies'),
            ],
            [
                'url'   => route('admin.companies.edit', ['id' => $company->id]),
                'label' => __('admin.titles.company.edit'),
            ]
        ]" />
    </x-slot>

    {{-- Company --}}
    <x-containers.main>
        <x-containers.title>
            {{ __('admin.buttons.company.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Company Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company.name') }}" wire:model="companyName" required />
                </div>

                {{-- World ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company.world_id') }}" wire:model="worldId" required />
                </div>

                {{-- CoC Number --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company.coc_number') }}" wire:model="cocNumber" required />
                </div>

                <div class="cols-span-1"></div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('admin.labels.company.owner') }}
                    </label>
                    <livewire:async-select
                        :options="$players->map(fn($player) => ['label' => $player->username, 'value' => $player->uuid])"
                        wire:model="selectedPlayer"
                        :min-search-length="2"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateCompany">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    {{-- Employees --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>
                {{ __('admin.titles.company.employees') }}
            </x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchEmployees" wire:model.live="searchEmployees" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.add-employee', ['id' => $company->id]) }}">
                    {{ __('admin.buttons.company.add_employee') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>
                            {{ __('admin.labels.company.employee_name') }}
                        </x-tables.table-header>
                        <x-tables.table-header>
                            {{ __('admin.labels.company.employee_role') }}
                        </x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($employees as $employee)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $employee->player->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($employee->role) }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removeEmployee('{{ $employee->player_uuid }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="3">
                                {{ __('admin.messages.company.employees_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($employees->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $employees->links() }}
                            <x-tables.per-page-select wire:model.live="employeesPerPage">
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

    {{-- Bankaccounts --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>
                {{ __('admin.titles.company.bank_accounts') }}
            </x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchBankAccounts" wire:model.live="searchBankAccounts" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.add-bank-account', ['id' => $company->id]) }}">
                    {{ __('admin.buttons.company.add_bank_account') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_number') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_balance') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_is_main') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_account_currency') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($bankAccounts as $bankAccount)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $bankAccount->id }}</x-tables.table-data>
                            <x-tables.table-data>
                                {{ Number::currency($bankAccount->balance, $bankAccount->currency) }}
                            </x-tables.table-data>
                            <x-tables.table-data>
                                @if ($bankAccount->is_main)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.true') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.false') }}</span>
                                @endif
                            </x-tables.table-data>
                            <x-tables.table-data>{{ strtoupper($bankAccount->currency) }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="">
                                    {{ __('general.buttons.edit') }}
                                </x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeBankAccount('{{ $bankAccount->id }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.company.bank_accounts_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($bankAccounts->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $bankAccounts->links() }}
                            <x-tables.per-page-select wire:model.live="bankAccountsPerPage">
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

    {{-- Plots --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>
                {{ __('admin.titles.company.plots') }}
            </x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchPlots" wire:model.live="searchPlots" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.add-plot', ['id' => $company->id]) }}">
                    {{ __('admin.buttons.company.add_plot') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.company.plot_name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.plot_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.world_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.plot_location') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($plots as $plot)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $plot->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->plot_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->world_id }}</x-tables.table-data>
                            <x-tables.table-data>[{{ $plot->min_x }}, {{ $plot->min_y }}, {{ $plot->min_z }}] - [{{ $plot->max_x }}, {{ $plot->max_y }}, {{ $plot->max_z }}]</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removePlot('{{ $plot->id }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.company.plots_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($plots->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $plots->links() }}
                            <x-tables.per-page-select wire:model.live="plotsPerPage">
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

    {{-- PIN Consoles --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <x-containers.title>
                {{ __('admin.titles.company.add_pin_console') }}
            </x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchPinConsoles" wire:model.live="searchPinConsoles" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.add-pin-console', ['id' => $company->id]) }}">
                    {{ __('admin.buttons.company.add_pin_console') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.company.pin_console_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.pin_console_account') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.pin_console_location') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.city') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.city_regions.country') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.world_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.pin_console_is_active') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($pinConsoles as $pinConsole)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $pinConsole->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->account_id }}</x-tables.table-data>
                            <x-tables.table-data>[{{ $pinConsole->x }}, {{ $pinConsole->y }}, {{ $pinConsole->z }}]</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->city }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->country->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->world_id }}</x-tables.table-data>
                            <x-tables.table-data>
                                @if ($pinConsole->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.yes') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.no') }}</span>
                                @endif
                            </x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.pin-consoles.edit', ['id' => $pinConsole->id]) }}">
                                    {{ __('general.buttons.edit') }}
                                </x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePinConsole('{{ $pinConsole->id }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.company.pin_consoles_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($pinConsoles->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $pinConsoles->links() }}
                            <x-tables.per-page-select wire:model.live="pinConsolesPerPage">
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
    
    {{-- Remove Employee Modal --}}
    <x-modals.modal wire:model="removeEmployeeModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.company.remove_employee') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <p class="text-center text-gray-600 dark:text-gray-300">
                {!! __('admin.messages.company.remove_employee_confirmation', ['name' => $employeeToRemove->player->username ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('removeEmployeeModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyEmployee">
                {{ __('general.buttons.remove') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Remove Bank Account Modal --}}
    <x-modals.modal wire:model="removeBankAccountModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.company.remove_bank_account') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <p class="text-center text-gray-600 dark:text-gray-300">
                {!! __('admin.messages.company.remove_bank_account_confirmation', ['id' => $bankAccountToRemove->id ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('removeBankAccountModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyBankAccount">
                {{ __('general.buttons.remove') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Remove Plot Modal --}}
    <x-modals.modal wire:model="removePlotModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.company.remove_plot') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <p class="text-center text-gray-600 dark:text-gray-300">
                {!! __('admin.messages.company.remove_plot_confirmation', ['id' => $plotToRemove->plot_id ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('removePlotModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="unlinkPlot">
                {{ __('general.buttons.remove') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Remove PIN Console Modal --}}
    <x-modals.modal wire:model="removePinConsoleModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.company.remove_pin_console') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <p class="text-center text-gray-600 dark:text-gray-300">
                {!! __('admin.messages.company.remove_pin_console_confirmation', ['id' => $pinConsoleToRemove->id ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('removePinConsoleModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPinConsole">
                {{ __('general.buttons.remove') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
