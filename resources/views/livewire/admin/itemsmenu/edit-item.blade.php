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
                'url'   => route('admin.itemsmenu.item.new'),
                'label' => __('admin.buttons.item_edit', ['id' => $item->id]),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.item_edit') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-6">
                {{-- Internal ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.internal_id') }}" wire:model="internalId" required />
                </div>
                {{-- Icon name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.item_name') }}" wire:model="name" required />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-6 mt-4">
                {{-- Item name --}}
                <div class="col-span-1">
                    <x-forms.select id="categorySelect" wire:model.live="categoryId" label="{{ __('admin.labels.category') }}">
                        <option value="">{{ __('general.placeholders.select_option') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
                {{-- Icon Material --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.icon_material') }}" wire:model="iconMaterial" required />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-6 mt-4">
                {{-- Display name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.display_name') }}" wire:model="displayName" required/>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-6 mt-4">
                {{-- Lore --}}
                <div class="col-span-2">
                    <x-forms.label>{{ __('admin.labels.lore') }}</x-forms.label>
                
                    <div class="space-y-2 mt-2">
                        @foreach ($lore as $index => $line)
                            <div class="flex items-center gap-2 w-full">
                                <x-forms.text-input 
                                    wire:model="lore.{{ $index }}" 
                                    :label="__('admin.labels.lore_line', ['number' => $index + 1])" 
                                    inline
                                />

                                <button 
                                    type="button"
                                    tabindex="-1" 
                                    class="text-red-500 hover:text-red-700 font-bold text-2xl mb-1 cursor-pointer"
                                    wire:click="removeLoreLine({{ $index }})"
                                    @if (count($lore) <= 1) disabled @endif
                                >
                                    &times;
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <x-buttons.secondary-button class="mt-2" wire:click="addLoreLine">
                        + {{ __('admin.buttons.add_lore_line') }}
                    </x-buttons.secondary-button>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-6 mt-4">
                {{-- Extra fields if the category is food --}}
                @if ($isFood)
                    {{-- Shelf life --}}
                    <div class="col-span-1">
                        {{-- TODO: change to number input --}}
                        <x-forms.text-input label="{{ __('admin.labels.shelf_life') }}" wire:model="shelfLife" />
                    </div>

                    {{-- Expired prefix --}}
                    <div class="col-span-1">
                        <x-forms.select id="expiredPrefix" wire:model.live="expiredPrefix" label="{{ __('admin.labels.expired_prefix') }}">
                            <option value="">{{ __('general.placeholders.select_option') }}</option>
                            @foreach($expiredPrefixes as $prefix)
                                <option value="{{ $prefix }}">{{ $prefix }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                @endif
            </div>
            
            <div class="flex justify-end items-center gap-x-4 mt-6">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="updateItem">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
