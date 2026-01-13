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
                'url'   => route('admin.companies.render'),
                'label' => __('sidebar.companies'),
            ],
            [
                'url'   => route('admin.companies.edit', ['id' => $company->id]),
                'label' => __('admin.titles.company_edit'),
            ]
        ]" />
    </x-slot>

    {{-- Company --}}
    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.company_create') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Company Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company_name') }}" wire:model="companyName" required />
                </div>

                {{-- World ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company_world_id') }}" wire:model="worldId" required />
                </div>

                {{-- CoC Number --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company_coc_number') }}" wire:model="cocNumber" required />
                </div>

                <div class="cols-span-1"></div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        {{ __('admin.labels.company_owner') }}
                    </label>
                    <livewire:async-select
                        :options="$players->map(fn($player) => ['label' => $player->username, 'value' => $player->uuid])"
                        wire:model="selectedPlayer"
                        :min-search-length="2"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="updateCompany">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    {{-- Employees --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">
                {{ __('admin.titles.employees') }}
            </h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchEmployees" wire:model.live="searchEmployees" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.add-employee', ['id' => $company->id]) }}">
                    {{ __('admin.buttons.add_employee') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>
                            {{ __('admin.labels.employee_name') }}
                        </x-tables.table-header>
                        <x-tables.table-header>
                            {{ __('admin.labels.employee_role') }}
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
                                {{ __('admin.messages.employees_no_records') }}
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
            <h1 class="text-xl font-semibold dark:text-white mb-4">
                {{ __('admin.titles.bank_accounts') }}
            </h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchBankAccounts" wire:model.live="searchBankAccounts" />
                <x-buttons.primary-button size="sm" href="">
                    {{ __('admin.buttons.add_bank_account') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.bank_account_number') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.bank_account_balance') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.bank_account_is_main') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.bank_account_currency') }}</x-tables.table-header>
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
                                <x-tables.danger-action wire:click="removeBankAccount('{{ $bankAccount->id }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.bank_accounts_no_records') }}
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
            <h1 class="text-xl font-semibold dark:text-white mb-4">
                {{ __('admin.titles.plots') }}
            </h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchPlots" wire:model.live="searchPlots" />
                <x-buttons.primary-button size="sm" wire:click="">
                    {{ __('admin.buttons.add_plot') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.plot_name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.plot_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company_world_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.plot_location') }}</x-tables.table-header>
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
                                <x-tables.danger-action wire:click="removePlot('{{ $plot->uuid }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.plots_no_records') }}
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

    {{-- Pin Consoles --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">
                {{ __('admin.titles.pin_consoles') }}
            </h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchPinConsoles" wire:model.live="searchPinConsoles" />
                <x-buttons.primary-button size="sm" wire:click="">
                    {{ __('admin.buttons.add_pin_console') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.pin_console_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.pin_console_account') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.pin_console_location') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company_world_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.pin_console_is_active') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($pinConsoles as $pinConsole)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $pinConsole->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->account_id }}</x-tables.table-data>
                            <x-tables.table-data>[{{ $pinConsole->x }}, {{ $pinConsole->y }}, {{ $pinConsole->z }}]</x-tables.table-data>
                            <x-tables.table-data>{{ $pinConsole->world_id }}</x-tables.table-data>
                            <x-tables.table-data>
                                @if ($pinConsole->is_active)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.true') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.false') }}</span>
                                @endif
                            </x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removePinConsole('{{ $pinConsole->uuid }}')">
                                    {{ __('general.buttons.remove') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.pin_consoles_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">

                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Assign Employee Modal --}}

    {{-- Assign Plot Modal --}}
    
    {{-- Remove Employee Modal --}}
    <x-modals.modal wire:model="removeEmployeeModal">
        <x-slot name="title">
            <div class="w-full flex justify-center">
                {{ __('admin.buttons.remove_employee') }}
            </div>
        </x-slot>
        <x-slot name="content">
            <p class="text-center text-gray-600 dark:text-gray-300">
                {!! __('admin.messages.company_remove_employee_confirmation', ['name' => $employeeToRemove->player->username ?? '']) !!}
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


    {{-- Remove Plot Modal --}}


    {{-- Remove Pin Console Modal --}}
</div>
