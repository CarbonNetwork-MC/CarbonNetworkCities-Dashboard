<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('wholesale.start', ['step' => $step]),
                'label' => __('wholesale.titles.start'),
            ],
        ]" />
    </x-slot>

    <div class="flex items-center flex-col">
        <x-containers.main class="grid grid-cols-1 gap-2 w-[50%]">
            <ol class="w-full flex justify-center items-center text-sm font-medium text-center text-body sm:text-base">
                @for ($i = 1; $i <= $numberOfSteps; $i++)
                    @php
                        $isActive = $i < $step;
                        $isCurrent = $i == $step;
                    @endphp

                    <li class="flex items-center {{ $i > 1 ? 'ml-2' : '' }}">
                        <div class="flex items-center gap-x-2 {{ $isActive || $isCurrent ? 'text-blue-500' : '' }}">
                            <i class="fi fi-rr-circle-{{ $i }}"></i>
                            <span>{{ __('wholesale.stepper.step' . $i) }}</span>

                            @if ($i < $numberOfSteps)
                                <hr class="w-32 h-1 border-0 rounded-md hidden sm:block
                                    {{ $isActive ? 'bg-blue-500' : 'bg-gray-300' }}">
                            @endif
                        </div>
                    </li>
                @endfor
            </ol>
        </x-containers.main>
    </div>

    <div class="flex items-center flex-col mt-4">
        <x-containers.main class="grid grid-cols-1 gap-2 w-[50%]">
            <x-containers.title>
                @if ($step == 1)
                    {{ __('wholesale.titles.companies') }}
                @elseif ($step == 2)
                    {{ __('wholesale.titles.wholesalers') }}
                @endif
            </x-containers.title>

            <div class="mt-4">
                @if ($step == 1)
                    <x-tables.table-striped>
                        <x-slot name="headers">
                            <tr>
                                <x-tables.table-header>{{ __('admin.labels.companies.name') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('admin.labels.companies.coc_number') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('admin.labels.companies.coc_type') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('admin.labels.companies.owner') }}</x-tables.table-header>
                            </tr>
                        </x-slot>
                        <x-slot name="rows">
                            @foreach ($companies as $company)
                                <x-tables.table-row wire:click="selectCompany({{ $company->id }})" class="cursor-pointer">
                                    <x-tables.table-data>{{ $company->name }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $company->coc_number }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $company->coc_type ?? '-' }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $company->owner->username ?? __('admin.labels.companies.no_owner_assigned') }}</x-tables.table-data>
                                </x-tables.table-row>
                            @endforeach
                        </x-slot>
                    </x-tables.table-striped>
                @elseif ($step == 2)
                    <x-tables.table-striped>
                        <x-slot name="headers">
                            <tr>
                                <x-tables.table-header>{{ __('wholesale.labels.country') }}</x-tables.table-header>
                                <x-tables.table-header>{{ __('wholesale.labels.name') }}</x-tables.table-header>
                            </tr>
                        </x-slot>
                        <x-slot name="rows">
                            @foreach ($wholesalers as $wholesaler)
                                <x-tables.table-row wire:click="selectWholesaler({{ $wholesaler->id }})" class="cursor-pointer">
                                    <x-tables.table-data>{{ $wholesaler->country->name }}</x-tables.table-data>
                                    <x-tables.table-data>{{ $wholesaler->name }}</x-tables.table-data>
                                </x-tables.table-row>
                            @endforeach
                        </x-slot>
                    </x-tables.table-striped>
                @endif
            </div>

            <div class="flex justify-end">
                <x-buttons.secondary-button wire:click="decrementStep" :disabled="$step == 1">
                    {{ __('wholesale.buttons.back') }}
                </x-buttons.secondary-button>
            </div>
        </x-containers.main>
    </div>
</div>
