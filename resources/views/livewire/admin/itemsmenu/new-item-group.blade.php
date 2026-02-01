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
                'url'   => route('admin.itemsmenu.render'),
                'label' => __('sidebar.itemsmenu'),
            ],
            [
                'url'   => route('admin.itemsmenu.item-group.new'),
                'label' => __('admin.titles.itemsmenu.create_item_group'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.itemsmenu.create_item_group') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.itemsmenu.name') }}" wire:model="name" required />
                </div>
                
                {{-- CoC Type --}}
                <div class="col-span-1">
                    <x-forms.label id="cocTypeSelect" required>
                        {{ __('admin.labels.itemsmenu.select_coc_type') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="cocTypeSelect"
                        :options="$cocTypes->map(fn($type) => ['value' => $type->id, 'label' => $type->name])"
                        wire:model.live="cocType"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Sellable --}}
                <div class="col-span-1 flex items-end mt-3">
                    <x-forms.checkbox label="{{ __('admin.labels.itemsmenu.sellable') }}" wire:model="sellable" />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createItemGroup">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    {{-- Item Group Items --}}
    <x-containers.main class="mt-4">
        <x-containers.title>
            {{ __('admin.titles.itemsmenu.item_group_items') }}
        </x-containers.title>

        <div class="space-y-4 mt-4">
            @foreach ($items as $index => $item)
                <div class="grid grid-cols-4 gap-4" wire:key="item-group-item-{{ $index }}">
                    {{-- Item --}}
                    <div class="col-span-1">
                        <div class="flex gap-x-2">
                            <x-forms.label for="itemSelect-{{ $index }}" id="itemSelect-{{ $index }}-label" required>
                                {{ __('admin.labels.itemsmenu.item') }} #{{ $index + 1 }}
                            </x-forms.label>
                            <i class="fi fi-rr-trash text-red-500 hover:text-red-600 cursor-pointer mb-2.5" wire:click="removeItem('{{ $index }}')"></i>
                        </div>
                        <livewire:async-select
                            id="itemSelect-{{ $index }}"
                            :options="$this->availableItemsFor($index)"
                            wire:model.live="items.{{ $index }}.item_id"
                            wire:key="items.{{ $index }}.item_id"
                            :min-search-length="2"
                        />
                    </div>

                    <div class="col-span-3"></div>

                    {{-- Price --}}
                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('admin.labels.itemsmenu.price') }}" wire:model="items.{{ $index }}.price" />
                    </div>
                    
                    {{-- Base Price --}}
                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('admin.labels.itemsmenu.base_price') }}" wire:model="items.{{ $index }}.base_price" required />
                    </div>

                    <div class="col-span-2"></div>

                    {{-- Sellable --}}
                    <div class="col-span-1">
                        <x-forms.checkbox label="{{ __('admin.labels.itemsmenu.sellable') }}" wire:model="items.{{ $index }}.sellable" />
                    </div>
                </div>
            @endforeach

            <x-buttons.secondary-button wire:click="addItem" class="flex items-center gap-x-2">
                <i class="fi fi-rr-plus text-green-500"></i>
                {{ __('admin.buttons.itemsmenu.add_item_to_group') }}
            </x-buttons.secondary-button>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createItemGroup">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
