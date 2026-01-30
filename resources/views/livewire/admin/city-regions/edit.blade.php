<div>
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('admin.dashboard.render'),
                'label' => '',
            ],
            [
                'url'   => route('admin.city-regions.render'),
                'label' => __('sidebar.city_regions'),
            ],
            [
                'icon' => '',
                'url' => route('admin.city-regions.edit', ['id' => $this->cityRegion->id]),
                'label' => __('admin.titles.city_regions.edit'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.city_regions.edit') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4">
                {{-- Internal Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.city_regions.internal_name') }}" wire:model="internalName" required />
                </div>

                {{-- Display Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.city_regions.display_name') }}" wire:model="displayName" required />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-4 mt-4">
                {{-- Country --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2.5">
                        {{ __('admin.labels.city_regions.country') }}
                    </label>
                    <livewire:async-select
                        :options="$countries->map(fn($country) => ['label' => $country->name, 'value' => $country->id])"
                        wire:model="selectedCountry"
                        :min-search-length="2"
                    />
                </div>

                {{-- City --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.city_regions.city') }}" wire:model="city" />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-4 mt-4">
                {{-- World ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.city_regions.world_id') }}" wire:model="worldId" required />
                </div>
            </div>

            <div class="grid grid-cols-6 gap-x-4 mt-4">
                {{-- Min X --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Min X" wire:model="minX" required />
                </div>

                {{-- Min Y --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Min Y" wire:model="minY" required />
                </div>

                {{-- Min Z --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Min Z" wire:model="minZ" required />
                </div>
            </div>

            <div class="grid grid-cols-6 gap-x-4 mt-4">
                {{-- Max X --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Max X" wire:model="maxX" required />
                </div>

                {{-- Max Y --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Max Y" wire:model="maxY" required />
                </div>

                {{-- Max Z --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Max Z" wire:model="maxZ" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateCityRegion">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
