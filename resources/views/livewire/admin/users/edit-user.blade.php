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
        <x-containers.title>
            {{ __('general.buttons.edit') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('general.labels.name') }}" wire:model="userName" required class="mb-5"/>
                </div>
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('general.labels.email') }}" wire:model="userEmail" required class="mb-5"/>
                </div>
                <div class="col-span-2"></div>
                <div class="col-span-1">
                    <x-forms.label for="language" required>
                        {{ __('admin.labels.users.select_language') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="language"
                        :options="$languages->map(fn($language) => ['value' => $language->id, 'label' => $language->name])"
                        wire:model="selectedLanguage"
                        :min-search-length="2"
                    />
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