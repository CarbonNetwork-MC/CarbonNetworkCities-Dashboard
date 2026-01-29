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
        ]" />
    </x-slot>

    {{-- Player --}}
    <x-containers.main>
        <x-containers.title>{{ __('admin.titles.players.edit') }}</x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- UUID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.players.uuid') }}" wire:model="uuid" />
                </div>

                {{-- Username --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.players.username') }}" wire:model="username" />
                </div>

                {{-- Level --}}
                <div class="col-span-1 w-1/2">
                    <x-forms.number-input label="{{ __('admin.labels.players.level') }}" wire:model="level" />
                </div>
                <div class="col-span-1"></div>

                {{-- Nationality --}}
                <div class="col-span-1">
                    <x-forms.label for="countrySelect">
                        {{ __('admin.labels.players.nationality') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="countrySelect"
                        :options="$countries->map(fn($country) => ['value' => $country->id, 'label' => $country->name])"
                        wire:model="selectedCountry"
                        :min-search-length="2"
                    />
                </div>

                {{-- Language --}}
                <div class="col-span-1">
                    <x-forms.label for="languageSelect">
                        {{ __('admin.labels.players.selected_language') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="languageSelect"
                        :options="$languages->map(fn($language) => ['value' => $language->id, 'label' => $language->name])"
                        wire:model="selectedLanguage"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Last Region --}}
                <div class="col-span-1">
                    <x-forms.label for="lastRegionSelect">
                        {{ __('admin.labels.players.last_region') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="lastRegionSelect"
                        :options="$regions->map(fn($region) => ['value' => $region->id, 'label' => $region->internal_name . ' - ' . $region->world_id])"
                        wire:model="lastRegion"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-3"></div>

                {{-- Playtime --}}
                <div class="col-span-1">
                    <x-forms.number-input 
                        id="playtime" 
                        label="{{ __('admin.labels.players.playtime') }}" 
                        wire:model="playtime" 
                        :disabled="!$updatePlaytime" 
                    />
                </div>

                <div class="col-span-1 flex items-end ml-6 mb-3">
                    <x-forms.checkbox 
                        label="{{ __('admin.labels.players.update_playtime') }}" 
                        wire:model.live="updatePlaytime" 
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updatePlayer">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>

    {{-- Prefixes --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.players.prefixes') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchPrefixes" wire:model.live="searchPrefixes" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.players.add-prefix', ['uuid' => $player->uuid]) }}">{{ __('general.buttons.add') }}</x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.prefix') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.selected') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($prefixes as $prefix)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $prefix->prefix }}</x-tables.table-data>
                            <x-tables.table-data>
                                @if($prefix->selected)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.true') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.false') }}</span>
                                @endif
                            </x-tables.table-data>
                            <x-tables.table-actions>
                                @if(!$prefix->selected)
                                    <x-tables.primary-action wire:click="assignPrefix('{{ $prefix->id }}')">{{ __('general.buttons.select') }}</x-tables.primary-action>
                                @endif
                                @if ($prefix->prefix !== 'Citizen')
                                    <x-tables.primary-action href="{{ route('admin.players.edit-prefix', ['uuid' => $player->uuid, 'id' => $prefix->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                    <x-tables.danger-action wire:click="removePrefix('{{ $prefix->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                                @endif
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="3">
                                {{ __('admin.messages.players.prefixes_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($prefixes->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $prefixes->links() }}
                            <x-tables.per-page-select wire:model.live="prefixesPerPage">
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

    {{-- Chat Colors --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title :marginBottom="false">{{ __('admin.titles.players.chat_colors') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchChatColors" wire:model.live="searchChatColors" />
                <x-buttons.primary-button size="sm" href="">{{ __('general.buttons.add') }}</x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.chat_color') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.type') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.selected') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($chatColors as $chatColor)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $chatColor->color_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($chatColor->type) }}</x-tables.table-data>
                            <x-tables.table-data>
                                @if($chatColor->selected)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.true') }}</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-1 px-2.5 py-0.5 rounded-full">{{ __('general.false') }}</span>
                                @endif
                            </x-tables.table-data>
                            <x-tables.table-actions>
                                @if(!$chatColor->selected)
                                    <x-tables.primary-action wire:click="assignChatColor('{{ $chatColor->id }}')">{{ __('general.buttons.select') }}</x-tables.primary-action>
                                @endif
                                <x-tables.danger-action wire:click="removeChatColor('{{ $chatColor->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="4">
                                {{ __('admin.messages.players.chat_colors_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($chatColors->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $chatColors->links() }}
                            <x-tables.per-page-select wire:model.live="chatColorsPerPage">
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

    {{-- Bank Accounts --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.players.bank_accounts') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchBankAccounts" wire:model.live="searchBankAccounts" />
                <x-buttons.primary-button size="sm" href="">{{ __('general.buttons.add') }}</x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>#</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.balance') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.type') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.currency') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($bankAccounts as $bankAccount)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $bankAccount->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $bankAccount->balance }}</x-tables.table-data>
                            <x-tables.table-data>{{ ucfirst($bankAccount->type) }}</x-tables.table-data>
                            <x-tables.table-data>{{ $bankAccount->currency }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeBankAccount('{{ $bankAccount->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="5">
                                {{ __('admin.messages.players.bank_accounts_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($bankAccounts->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $bankAccounts->links() }}
                            <x-tables.per-page-select wire:model.live="bankAccountsPerPage">
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

    {{-- Plots --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.players.plots') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchPlots" wire:model.live="searchPlots" />
                <x-buttons.primary-button size="sm" href="">{{ __('general.buttons.add') }}</x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.plot_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.world') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($plots as $plot)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $plot->plot_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $plot->world_id }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removePlot('{{ $plot->id }}')">{{ __('general.buttons.remove') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="4">
                                {{ __('admin.messages.players.plots_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($plots->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $plots->links() }}
                            <x-tables.per-page-select wire:model.live="plotsPerPage">
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

    {{-- Companies --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.players.companies') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchCompanies" wire:model.live="searchCompanies" />
                <x-buttons.primary-button size="sm" href="">{{ __('general.buttons.add') }}</x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.players.coc_number') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($companies as $company)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $company->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->coc_number }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCompany('{{ $company->id }}')">{{ __('general.buttons.remove') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="3">
                                {{ __('admin.messages.players.companies_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($companies->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $companies->links() }}
                            <x-tables.per-page-select wire:model.live="companiesPerPage">
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

    {{-- Past Usernames --}}
    <x-containers.main class="mt-4" x-data="{open: true}">
        <div class="flex justify-between">
            <div class="flex items-center gap-x-4">
                <x-containers.title>{{ __('admin.titles.players.past_usernames') }}</x-containers.title>
                <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" x-on:click="open = !open">
                    <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!open"></i>
                    <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="open"></i>
                </div>
            </div>
            <div class="flex items-center gap-x-4" x-show="open">
                <x-forms.search-bar id="searchPastUsernames" wire:model.live="searchPastUsernames" />
            </div>
        </div>

        <div class="mt-4" x-show="open">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.players.username') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('general.labels.created_at') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($pastUsernames as $pastUsername)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $pastUsername->username }}</x-tables.table-data>
                            <x-tables.table-data>{{ $pastUsername->created_at }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.danger-action wire:click="removePastUsername('{{ $pastUsername->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state :colspan="3">
                                {{ __('admin.messages.players.past_usernames_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($pastUsernames->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $pastUsernames->links() }}
                            <x-tables.per-page-select wire:model.live="pastUsernamesPerPage">
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

    {{-- Delete Prefix Modal --}}
    <x-modals.modal wire:model="showRemovePrefixModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.delete_prefix') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.players.delete_prefix_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemovePrefixModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPrefix">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Chat Color Modal --}}
    <x-modals.modal wire:model="showRemoveChatColorModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.delete_chat_color') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.players.delete_chat_color_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemoveChatColorModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyColor">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Bank Account Modal --}}
    <x-modals.modal wire:model="showRemoveBankAccountModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.delete_bank_account') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.players.delete_bank_account_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemoveBankAccountModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyBankAccount">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Unlink Plot Modal --}}
    <x-modals.modal wire:model="showRemovePlotModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.unlink_plot') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.players.unlink_plot_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemovePlotModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="unlinkPlot">
                {{ __('general.buttons.unlink') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Unlink Company Modal --}}
    <x-modals.modal wire:model="showRemoveCompanyModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.unlink_company') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.players.unlink_company_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemoveCompanyModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="unlinkCompany">
                {{ __('general.buttons.unlink') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Past Username Modal --}}
    <x-modals.modal wire:model="showRemovePastUsernameModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.players.delete_past_username') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.players.delete_past_username_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('showRemovePastUsernameModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyPastUsername">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
