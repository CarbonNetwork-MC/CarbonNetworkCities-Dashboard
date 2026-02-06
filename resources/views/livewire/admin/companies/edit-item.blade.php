<div>
    {{-- Breacrumbs --}}
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
                'url' => route('admin.companies.edit-item', ['companyId' => $company->id, 'itemId' => $item->id]),
                'label' => __('admin.titles.companies.edit_item'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('admin.titles.companies.edit_item') }}</x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Item --}}
                <div class="col-span-1">
                    <x-forms.label for="itemSelect">
                        {{ __('admin.labels.companies.item') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="itemSelect"
                        :options="$items->map(fn($item) => ['value' => $item->id, 'label' => $item->internal_id])"
                        wire:model="selectedItem"
                        placeholder="{{ __('general.placeholders.select_option') }}"
                        required
                    />
                </div>

                {{-- Price --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.price') }}" wire:model="price" min="0" placeholder="{{ $item->base_price }}" required />
                </div>

                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.base_price') }}" wire:model="basePrice" min="0" placeholder="{{ $item->base_price }}" required />
                </div>

                <div class="col-span-1"></div>

                {{-- Sellable --}}
                <div class="col-span-1 flex items-end ml-6 mb-3">
                    <x-forms.checkbox label="{{ __('admin.labels.companies.sellable') }}" wire:model="sellable" />
                </div>
            </div>

            <div class="flex justify-end gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateItem">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
