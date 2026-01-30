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
                'url'   => route('admin.players.add-chat-color', ['uuid' => $player->uuid]),
                'label' => __('admin.titles.players.add_chat_color'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.players.add_chat_color') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Type --}}
                <div class="col-span-01">
                    <x-forms.select label="{{ __('admin.labels.players.type') }}" wire:model.live="type" required>
                        <option value="">{{ __('general.placeholders.select_option') }}</option>
                        @foreach ($types as $typeOption)
                            <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
                        @endforeach
                    </x-forms.select>
                </div>

                {{-- Color --}}
                <div class="col-span-1">
                    <x-forms.label>{{ __('admin.labels.players.chat_color') }}</x-forms.label>
                    <livewire:async-select
                        id="chatColorSelect"
                        :options="$allChatColors->map(fn($color) => ['value' => $color->id, 'label' => $color->name])"
                        wire:model="color"
                        wire:key="chat-color-select-{{ $type }}"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Selected --}}
                <div class="col-span-1 mt-6">
                    <x-forms.checkbox
                        id="selected"
                        label="{{ __('admin.labels.players.selected') }}"
                        wire:model="selected"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addChatColor">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
