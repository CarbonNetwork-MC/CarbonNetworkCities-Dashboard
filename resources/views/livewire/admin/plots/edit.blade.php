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
                'url'   => route('admin.plots.edit', ['id' => $plot->id]),
                'label' => __('admin.titles.plots.edit'),
            ]
        ]" />
    </x-slot>

    {{-- Plot --}}
    <x-containers.main x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.plots.edit') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
        </div>

        <div class="mt-6" x-show="open">
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
                    <x-forms.text-area label="{{ __('admin.labels.plots.description') }}" wire:model="description" />
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
                        checked="{{ $forSale }}"
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
                <x-buttons.primary-button wire:click="updatePlot">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    {{-- Members --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.plots.members') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchMembers" wire:model.live="searchMembers" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.plots.add-member', ['id' => $plot->id]) }}">{{ __('general.buttons.add') }}</x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.uuid') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.username') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.employee_role') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($members as $member)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $member->player_uuid }}</x-tables.table-data>
                            <x-tables.table-data>{{ $member->player->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($member->role) }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removeMember('{{ $member->player_uuid }}')">{{ __('general.buttons.remove') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="4">
                                {{ __('admin.messages.plots.members_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($members->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $members->links() }}
                            <x-tables.per-page-select wire:model.live="membersPerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </x-tables.per-page-select>
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Fridges --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.plots.fridges') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>ID</x-tables.table-header>
                        <x-tables.table-header>Type</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($fridges as $fridge)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $fridge->id}}</x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($fridge->type) }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removeFridge('{{ $fridge->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="3">
                                {{ __('admin.messages.plots.fridges_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Remove Member Modal --}}
    <x-modals.modal wire:model="showRemoveMemberModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.plots.remove_member') }}</div></x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.plots.remove_member_confirmation', ['member' => $selectedMember?->player?->username]) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemoveMemberModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyMember">
                {{ __('general.buttons.remove') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Fridge Modal --}}
    <x-modals.modal wire:model="showRemoveFridgeModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.plots.delete_fridge') }}</div></x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.plots.delete_fridge_confirmation', ['id' => $selectedFridge?->id]) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemoveFridgeModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyFridge">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
