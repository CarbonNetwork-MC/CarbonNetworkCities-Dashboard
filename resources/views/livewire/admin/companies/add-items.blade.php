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
                'url' => route('admin.companies.add-items', ['id' => $company->id]),
                'label' => __('admin.titles.companies.add_items'),
            ]
        ]" />
    </x-slot>

    <x-containers.main class="flex justify-between">
        <div class="flex items-center gap-x-4">
            <x-buttons.secondary-button wire:click="addItem" class="flex items-center gap-x-2">
                <i class="fi fi-rr-plus text-green-500"></i>
                {{ __('admin.buttons.companies.add_item') }}
            </x-buttons.secondary-button>
            <x-buttons.secondary-button wire:click="$set('showAddItemGroupModal', true)" class="flex items-center gap-x-2">
                <i class="fi fi-rr-plus text-green-500"></i>
                {{ __('admin.buttons.companies.add_item_group') }}
            </x-buttons.secondary-button>
        </div>
        <x-buttons.primary-button wire:click="addItems">
            {{ __('general.buttons.add') }}
        </x-buttons.primary-button>
    </x-containers.main>

    <div class="grid grid-cols-2 gap-4 mt-4">
        @foreach ($items as $index => $item)
            <x-containers.main class="col-span-1" wire:key="item-group-item-{{ $index }}">
                <div class="grid grid-cols-2 gap-4">

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

                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('admin.labels.itemsmenu.price') }}" wire:model="items.{{ $index }}.price" />
                    </div>
                    
                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('admin.labels.itemsmenu.base_price') }}" wire:model="items.{{ $index }}.base_price" required />
                    </div>

                    <div class="col-span-1"></div>

                    <div class="col-span-1">
                        <x-forms.checkbox label="{{ __('admin.labels.itemsmenu.sellable') }}" wire:model="items.{{ $index }}.sellable" />
                    </div>
                </div>

                <div class="flex justify-end items-center gap-x-4 mt-6">
                    <x-forms.required-fields />
                    <x-buttons.primary-button wire:click="addItems">
                        {{ __('general.buttons.add') }}
                    </x-buttons.primary-button>
                </div>
            </x-containers.main>
        @endforeach
    </div>

    {{-- Add Item Group Modal --}}
    <x-modals.modal wire:model="showAddItemGroupModal">
        <x-slot name="title">
            {{ __('admin.titles.companies.add_item_group') }}
        </x-slot>
        <x-slot name="content">
            <div class="flex flex-col items-center">
                <x-forms.label for="itemGroupSelect" required>
                    {{ __('admin.labels.companies.item_group') }}
                </x-forms.label>
                <div class="w-1/2">
                    <livewire:async-select
                        id="itemGroupSelect"
                        :options="$itemGroups->map(fn($group) => ['value' => $group->id, 'label' => $group->name])"
                        wire:model.live="selectedItemGroupId"
                        wire:key="selectedItemGroup"
                        :min-search-length="2"
                    />
                </div>
            </div>
            @if ($selectedItemGroup)
                <h3 class="mt-4 font-medium">{{ __('admin.labels.companies.items_in_group') }}:</h3>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @forelse ($selectedItemGroup->items as $item)
                        <div class="col-span-1 bg-gray-800 rounded-md px-4 py-2">
                            {{ $item->internal_id }}
                        </div>
                    @empty

                    @endforelse
                </div>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showAddItemGroupModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.primary-button wire:click="addItemGroup">
                {{ __('general.buttons.add') }}
            </x-buttons.primary-button>
        </x-slot>
    </x-modals.modal>"
</div>
