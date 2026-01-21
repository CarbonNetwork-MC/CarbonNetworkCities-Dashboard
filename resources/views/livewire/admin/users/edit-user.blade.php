<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url'  => route('dashboard.render'),
                'label'=> '',
            ],
            [
                'url'   => route('admin.users.render'),
                'label' => __('sidebar.users'),
            ],
            [
                'url'   => route('admin.users.edit', ['uuid' => $user->uuid]),
                'label' => __('admin.titles.users.edit'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('general.buttons.edit') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4">
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('general.labels.name') }}" wire:model="userName" required class="mb-5"/>
                    <x-forms.text-input label="{{ __('general.labels.email') }}" wire:model="userEmail" required class="mb-5"/>
                    <x-forms.select id="languageSelect" wire:model="selectedLanguage" label="{{ __('admin.labels.languages') }}">
                        <option value="">{{ __('general.placeholders.select_option') }}</option>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="updateUser">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>