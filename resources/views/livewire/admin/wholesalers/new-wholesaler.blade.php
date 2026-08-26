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
                'url'   => route('admin.wholesalers.new'),
                'label' => __('admin.titles.wholesalers.add'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('admin.titles.wholesalers.add') }}</x-containers.title>

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
                <x-buttons.primary-button wire:click="createWholesaler">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
