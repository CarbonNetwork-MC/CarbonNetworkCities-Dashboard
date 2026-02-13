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
                'url'   => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.dashboard'),
            ],
        ]" />
    </x-slot>

    <div class="grid grid-cols-3 gap-4 h-full md:h-132">
        {{-- Stock Overview --}}
        <div class="col-span-1">
            <x-containers.main class="h-full">
                <x-containers.title href="{{ route('company.stock.render', ['companyId' => $company->id]) }}">{{ __('company.titles.stock_overview') }}</x-containers.title>
                
                <div class="flex flex-col justify-between h-full">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                        @forelse ($companyStock as $stockItem)
                            @php
                                $percentage = min(100, ($stockItem->quantity / max(1, $stockItem->preferred_stock_level)) * 100);

                                $color = $percentage > $stockItem->warning_threshold
                                    ? 'bg-emerald-500'
                                    : ($percentage > $stockItem->critical_threshold
                                        ? 'bg-yellow-500'
                                        : 'bg-red-500');
                            @endphp

                            <div class="rounded-xl bg-white dark:bg-gray-800 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-black dark:text-white text-sm">
                                            {{ $stockItem->item->item->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $stockItem->quantity }} {{ __('company.labels.units') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 dark:bg-gray-300 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $color }}"
                                            style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 md:col-span-3">
                                <p class="text-gray-500 dark:text-gray-400">{{ __('company.messages.stock_no_records') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($companyStock->hasPages())
                        <div class="mt-2 pb-4 flex justify-between items-center text-sm">
                            @if ($companyStock->onFirstPage())
                                <button disabled
                                        class="px-3 py-1 rounded bg-gray-400 cursor-not-allowed">
                                    <i class="fi fi-rr-arrow-small-left"></i>
                                </button>
                            @else
                                <button wire:click="previousPage('{{ $companyStock->getPageName() }}')"
                                    class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 cursor-pointer">
                                    <i class="fi fi-rr-arrow-small-left"></i>
                                </button>
                            @endif

                            <span>
                                {{ $companyStock->currentPage() }} / {{ $companyStock->lastPage() }}
                            </span>

                            @if ($companyStock->hasMorePages())
                                <button wire:click="nextPage('{{ $companyStock->getPageName() }}')"
                                    class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300 cursor-pointer">
                                    <i class="fi fi-rr-arrow-small-right"></i>
                                </button>
                            @else
                                <button disabled
                                    class="px-3 py-1 rounded bg-gray-400 cursor-not-allowed">
                                    <i class="fi fi-rr-arrow-small-right"></i>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </x-containers.main>
        </div>

        {{-- Best Selling Products --}}
        <div class="col-span-1">
            <x-containers.main class="h-full">
                <x-containers.title>{{ __('company.titles.best_selling_products') }}</x-containers.title>
            </x-containers.main>
        </div>

        {{-- Employee Overview --}}
        <div class="col-span-1">
            <x-containers.main class="h-full">
                <x-containers.title href="{{ route('company.employees.render', ['companyId' => $company->id]) }}">{{ __('company.titles.employee_overview') }}</x-containers.title>

                <div class="flex flex-col justify-between h-full">
                    <div class="grid grid-cols-2 gap-4 mt-2">
                        {{-- Owner --}}
                        @if ($company->owner)
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 overflow-hidden">
                                    <img src="https://cravatar.eu/avatar/{{ $company->owner->uuid }}/64.png" alt="{{ $company->owner->username }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold text-black dark:text-white text-sm">
                                        {{ $company->owner->username }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('company.roles.owner') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Employees --}}
                        @forelse ($employees as $employee)
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 overflow-hidden">
                                    <img src="https://cravatar.eu/avatar/{{ $employee->player->uuid }}/64.png" alt="{{ $employee->player->username }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-semibold text-black dark:text-white text-sm">
                                        {{ $employee->player->username }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('company.roles.' . $employee->role) }}
                                    </p>
                                </div>
                            </div>
                        @empty

                        @endforelse
                    </div>
                </div>
            </x-containers.main>
        </div>

        {{-- Bank Accounts Overview --}}
        @if ($hasPermission)
            <div class="col-span-1">
                <x-containers.main class="h-full">
                    <x-containers.title href="#">{{ __('company.titles.bank_accounts_overview') }}</x-containers.title>

                    @forelse ($bankAccounts as $account)
                        <div class="flex gap-x-6 rounded-xl bg-white dark:bg-gray-900 mt-4 p-4">
                            <i class="fi fi-rr-piggy-bank text-black dark:text-white"></i>
                            <div class="w-full">
                                <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                    <p>#{{ $account->id }}</p>
                                    <p>{{ $account->is_main ? __('company.labels.is_main') : '' }}</p>
                                </div>
                                <div class="text-black dark:text-white">
                                    {{ $account->country->currency_symbol }}{{ number_format($account->balance, 2) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">{{ __('company.messages.bank_accounts_no_records') }}</p>
                    @endforelse
                </x-containers.main>
            </div>
        @endif

        {{-- Notifications --}}
        @if ($hasPermission)
            <div class="col-span-1">
                <x-containers.main class="h-full">
                    <x-containers.title>{{ __('company.titles.notifications') }}</x-containers.title>

                    
                </x-containers.main>
            </div>
        @endif

        {{-- Empty --}}
        <div class="col-span-1">

        </div>
    </div>
</div>
