<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('admin.dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('admin.companies.render'),
                'label' => __('sidebar.companies'),
            ],
            [
                'icon' => '',
                'url' => route('admin.companies.new'),
                'label' => __('admin.buttons.companies.create'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.buttons.companies.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Company Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.name') }}" wire:model="companyName" required />
                </div>

                {{-- World ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.world_id') }}" wire:model="worldId" required />
                </div>

                {{-- CoC Number --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.coc_number') }}" wire:model="cocNumber" required />
                </div>

                <div class="cols-span-1"></div>

                {{-- CoC Type --}}
                <div class="col-span-1">
                    <x-forms.label for="cocTypeSelect">
                        {{ __('admin.labels.companies.coc_type') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="cocTypeSelect"
                        :options="$cocTypes->map(fn($type) => ['label' => $type->name, 'value' => $type->id])"
                        wire:model="cocType"
                        :min-search-length="2"
                    />
                </div>

                {{-- Owner --}}
                <div class="col-span-1">
                    <x-forms.label for="ownerSelect">
                        {{ __('admin.labels.companies.owner') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="ownerSelect"
                        :options="$players->map(fn($player) => ['label' => $player->username, 'value' => $player->uuid])"
                        wire:model="selectedPlayer"
                        :min-search-length="2"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createCompany">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
