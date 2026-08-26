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
                'url'   => route('admin.wholesalers.render'),
                'label' => __('sidebar.wholesalers'),
            ],
            [
                'url'   => route('admin.wholesalers.edit', ['wholesalerId' => $wholesaler->id]),
                'label' => __('admin.titles.wholesalers.edit'),
            ]
        ]" />
    </x-slot>

    {{-- Wholesaler --}}
    <x-containers.main>
        <x-containers.title>{{ __('admin.titles.wholesalers.edit') }}</x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Country --}}
                <div class="col-span-1">
                    <x-forms.label for="countrySelect">
                        {{ __('admin.labels.wholesalers.country') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="countrySelect"
                        :options="$countries->map(fn($country) => ['label' => $country->name, 'value' => $country->id])"
                        wire:model="countryId"
                        :min-search-length="2"
                    />
                </div>

                {{-- Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.wholesalers.name') }}" wire:model="name" required />
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <x-buttons.primary-button wire:click="updateWholesaler">
                    {{ __('general.buttons.update') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    {{-- Employees --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.wholesalers.employees') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchEmployees" wire:model.live="searchEmployees" />
                <x-buttons.primary-button size="sm" href="">
                    {{ __('admin.buttons.add_employee') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.wholesalers.player') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.wholesalers.role') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($employees as $employee)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $employee->player->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ $employee->role }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="">
                                    {{ __('general.buttons.edit') }}
                                </x-tables.primary-action>
                                <x-tables.danger-action wire:click="deleteEmployee({{ $employee->id }})">
                                    {{ __('general.buttons.delete') }}
                                </x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="3">
                                {{ __('admin.messages.wholesalers.no_employees') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">

                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Items --}}

</div>
