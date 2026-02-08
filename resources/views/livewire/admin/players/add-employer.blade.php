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
                'url'   => route('admin.players.render'),
                'label' => __('sidebar.players'),
            ],
            [
                'url'   => route('admin.players.edit', ['uuid' => $player->uuid]),
                'label' => __('admin.titles.players.edit'),
            ],
            [
                'url'   => route('admin.players.add-employer', ['uuid' => $player->uuid]),
                'label' => __('admin.titles.players.add_employer'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.players.add_employer') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4 gap-y-6">
                {{-- Company Id --}}
                <div class="col-span-1">
                    <x-forms.label>{{ __('admin.labels.players.company') }}</x-forms.label>
                    <livewire:async-select
                        id="companySelect"
                        :options="$companies->map(fn($company) => ['value' => $company->id, 'label' => $company->name])"
                        wire:model="companyId"
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

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addEmployer">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
