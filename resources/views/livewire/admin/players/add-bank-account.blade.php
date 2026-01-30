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
                'url'   => route('admin.players.add-bank-account', ['uuid' => $player->uuid]),
                'label' => __('admin.titles.players.add_bank_account'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.players.add_bank_account') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Balance --}}
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('admin.labels.players.balance') }}" wire:model="balance" required />
                </div>

                {{-- Currency --}}
                <div class="col-span-1">
                    <x-forms.label>{{ __('admin.labels.players.currency') }}</x-forms.label>
                    <livewire:async-select
                        id="currencySelect"
                        :options="$currencies->map(fn($currency) => ['value' => $currency->currency, 'label' => $currency->name . ' - ' . $currency->currency])"
                        wire:model="currency"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Type --}}
                <div class="col-span-1">
                    <x-forms.select label="{{ __('admin.labels.players.type') }}" wire:model="type" required>
                        <option value="">{{ __('general.placeholders.select_option') }}</option>
                        @foreach($types as $typeOption)
                            <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addBankAccount">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
