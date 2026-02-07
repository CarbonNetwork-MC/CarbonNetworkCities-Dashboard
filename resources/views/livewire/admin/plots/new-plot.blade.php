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
                'url'   => route('admin.plots.render'),
                'label' => 'Plots',
            ],
            [
                'url'   => route('admin.plots.new'),
                'label' => __('admin.titles.plots.create'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.plots.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Plot ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.plot_id') }}" wire:model="plotId" required />
                </div>

                {{-- Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.name') }}" wire:model="name" required />
                </div>

                {{-- Description --}}
                <div class="col-span-1">
                    <x-forms.textarea label="{{ __('admin.labels.plots.description') }}" wire:model="description" />
                </div>
                
                <div class="col-span-1"></div>

                {{-- City --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.city') }}" wire:model="city" required />
                </div>

                {{-- Country --}}
                <div class="col-span-1">
                    <x-forms.label required>
                        {{ __('admin.labels.companies.country') }}
                    </x-forms.label>
                    <livewire:async-select
                        :options="$countries->map(fn($country) => ['label' => $country->name, 'value' => $country->id])"
                        wire:model.live="countryId"
                        :min-search-length="2"
                    />
                </div>

                {{-- World ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.world_id') }}" wire:model="worldId" required />
                </div>

                <div class="col-span-1"></div>

                {{-- Company --}}
                <div class="col-span-1">
                    <x-forms.label>
                        {{ __('admin.labels.players.company') }}
                    </x-forms.label>
                    <livewire:async-select
                        :options="$companies->map(fn($company) => ['label' => $company->name, 'value' => $company->id])"
                        wire:model.live="companyId"
                        :min-search-length="2"
                    />
                </div>

                {{-- Owner --}}
                <div class="col-span-1">
                    <x-forms.label>
                        {{ __('admin.labels.companies.owner') }}
                    </x-forms.label>
                    <livewire:async-select
                        :options="$players->map(fn($player) => ['label' => $player->username, 'value' => $player->uuid])"
                        wire:model.live="ownerUuid"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

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

                <div class="col-span-1"></div>

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

                <div class="col-span-1"></div>

                {{-- Price --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.plots.price') }}" wire:model="price" />
                </div>

                {{-- Type --}}
                <div class="col-span-1">
                    <x-forms.label required>
                        {{ __('admin.labels.players.type') }}
                    </x-forms.label>
                    <livewire:async-select
                        :options="collect(config('plots.types'))->map(fn($type) => ['label' => ucfirst($type), 'value' => $type])"
                        wire:model.live="type"
                        :min-search-length="2"
                    />
                </div>

                {{-- For Sale --}}
                <div class="col-span-1 flex items-end mb-3">
                    <x-forms.checkbox
                        label="{{ __('admin.labels.plots.for_sale') }}"
                        wire:model="forSale"
                    />
                </div>

                <div class="col-span-1"></div>

                {{-- TP X --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Teleport X" wire:model="tpX" />
                </div>

                {{-- TP Y --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Teleport Y" wire:model="tpY" />
                </div>

                {{-- TP Z --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Teleport Z" wire:model="tpZ" />
                </div>

                <div class="col-span-1"></div>

                {{-- TP Yaw --}}
                <div class="col-span-1">
                    <x-forms.label>
                        Teleport Yaw
                    </x-forms.label>
                    <livewire:async-select
                        :options="collect(config('plots.directions'))->map(fn($direction, $key) => ['label' => __('admin.labels.plots.' . $key), 'value' => $direction])"
                        wire:model.live="tpYaw"
                        :min-search-length="2"
                    />
                </div>

                {{-- TP Pitch --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Teleport Pitch" wire:model="tpPitch" />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createPlot">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
