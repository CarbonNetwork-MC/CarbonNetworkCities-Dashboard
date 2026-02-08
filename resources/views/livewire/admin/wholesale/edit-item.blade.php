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
                'url'   => route('admin.wholesale-items.render'),
                'label' => __('sidebar.wholesale_items'),
            ],
            [
                'url'   => route('admin.wholesale-items.edit', ['id' => $item->id]),
                'label' => __('admin.titles.wholesale_items.edit'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.wholesale_items.edit') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Item --}}
                <div class="col-span-1">
                    <x-forms.label for="itemSelect" required>
                        {{ __('admin.labels.wholesale_items.item') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="itemSelect"
                        :options="$allItems->map(fn($item) => ['label' => $item->name, 'value' => $item->id])"
                        wire:model="itemId"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-3"></div>

                {{-- Price --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.wholesale_items.price') }}" wire:model="price" required />
                </div>

                {{-- Max Amount --}}
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('admin.labels.wholesale_items.max_amount') }}" wire:model="maxAmount" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateItem">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
