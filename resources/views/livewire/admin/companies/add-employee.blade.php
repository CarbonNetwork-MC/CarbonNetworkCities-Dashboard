<div>
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
                'label' => __('admin.titles.company.edit'),
            ],
            [
                'url' => route('admin.companies.add-employee', ['id' => $company->id]),
                'label' => __('admin.titles.company.add_employee'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.titles.company.add_employee') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- UUID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.player_uuid') }}" wire:model="playerUuid" required />
                </div>

                {{-- Role --}}
                <div class="col-span-1">
                    <x-forms.select label="{{ __('admin.labels.company.employee_role') }}" wire:model="role" required>
                        <option value="">{{ __('admin.placeholders.company.select_role') }}</option>
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
