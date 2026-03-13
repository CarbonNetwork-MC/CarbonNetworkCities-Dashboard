<div>
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
                'label' => __('admin.titles.companies.edit'),
            ],
            [
                'url' => route('admin.companies.add-employee', ['id' => $company->id]),
                'label' => __('admin.titles.companies.add_employee'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.companies.add_employee') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- UUID --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2.5">
                        {{ __('admin.labels.companies.owner') }}
                    </label>
                    <livewire:async-select
                        :options="$players->map(fn($player) => ['label' => $player->username, 'value' => $player->uuid])"
                        wire:model="playerUuid"
                        :min-search-length="2"
                    />
                </div>

                {{-- Role --}}
                <div class="col-span-1">
                    <x-forms.select label="{{ __('admin.labels.companies.employee_role') }}" wire:model="role" required>
                        <option value="">{{ __('admin.placeholders.companies.select_role') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addEmployee">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
