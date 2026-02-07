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
                'url'   => route('profile.render'),
                'label' => __('sidebar.profile'),
            ]
        ]" />
    </x-slot>

    <div class="mx-20">
        <x-profile.profile-card>
            <x-slot name="title">
                {{ __('profile.titles.information') }}
            </x-slot>
            <x-slot name="description">
                {{ __('profile.descriptions.information') }}
            </x-slot>
            <x-slot name="form">
                <div class="grid grid-cols-3 gap-x-2 gap-y-4">
                    <div class="col-span-1 flex flex-col items-center">
                        <div class="w-1/2 aspect-square rounded-full overflow-hidden">
                            <img src="{{ $currentProfileImage
                                ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'U') . '&background=random&color=fff&size=256' }}" 
                                alt="{{ auth()->user()->name }}" class="w-full h-full object-cover"
                            >
                        </div>

                        <x-forms.file-input
                            wire:model.live="profileImage" 
                            wrapper:class="mt-2"
                            helper="{{ __('profile.placeholders.image_upload_helper') }}"
                        />
                    </div>

                    <div class="col-span-2"></div>

                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('profile.labels.username') }}" wire:model="username" required />
                    </div>

                    <div class="col-span-2"></div>

                    <div class="col-span-2">
                        <x-forms.text-input label="{{ __('profile.labels.email') }}" wire:model="email" required />
                    </div>
                </div>

                <div class="flex justify-end items-center gap-x-4">
                    <x-forms.required-fields />
                    <x-buttons.primary-button wire:click="saveChanges" >
                        {{ __('general.buttons.save') }}
                    </x-buttons.primary-button>
                </div>
            </x-slot>
        </x-profile.profile-card>

        <x-containers.divider height="0.5" />

        <x-profile.profile-card>
            <x-slot name="title">
                {{ __('profile.titles.password') }}
            </x-slot>
            <x-slot name="description">
                {{ __('profile.descriptions.password') }}
            </x-slot>
            <x-slot name="form">
                <div class="grid grid-cols-3 gap-x-2 gap-y-4">
                    <div class="col-span-2">
                        <x-forms.password-input label="{{ __('profile.labels.current_password') }}" type="password" wire:model="currentPassword" required />
                    </div>

                    <div class="col-span-1"></div>

                    <div class="col-span-2">
                        <x-forms.password-input label="{{ __('profile.labels.new_password') }}" type="password" wire:model="newPassword" required />
                    </div>

                    <div class="col-span-1"></div>

                    <div class="col-span-2">
                        <x-forms.password-input label="{{ __('profile.labels.confirm_password') }}" type="password" wire:model="newPassword_confirmation" required />
                    </div>
                </div>

                <div class="flex justify-end items-center gap-x-4">
                    <x-forms.required-fields />
                    <x-buttons.primary-button wire:click="updatePassword" >
                        {{ __('general.buttons.update') }}
                    </x-buttons.primary-button>
                </div>
            </x-slot>
        </x-profile.profile-card>

        <x-containers.divider height="0.5" />

        <x-profile.profile-card>
            <x-slot name="title">
                {{ __('profile.titles.unlink_player') }}
            </x-slot>
            <x-slot name="description">
                {{ __('profile.descriptions.unlink_player') }}
            </x-slot>
            <x-slot name="form">
                <div class="flex flex-col items-start gap-y-4">
                    <span class="text-sm text-black dark:text-white">
                        {{ __('profile.messages.unlink_player') }}
                    </span>
                    <x-buttons.danger-button wire:click="unlinkPlayer" class="mt-4">
                        {{ __('profile.buttons.unlink_player') }}
                    </x-buttons.danger-button>
                </div>
            </x-slot>
        </x-profile.profile-card>

        <x-containers.divider height="0.5" />

        <x-profile.profile-card>
            <x-slot name="title">
                {{ __('profile.titles.delete_account') }}
            </x-slot>
            <x-slot name="description">
                {{ __('profile.descriptions.delete_account') }}
            </x-slot>
            <x-slot name="form">
                <div class="flex flex-col items-start gap-y-4">
                    <span class="text-sm text-black dark:text-white">
                        {{ __('profile.messages.delete_account') }}
                    </span>
                    <x-buttons.danger-button wire:click="deleteAccount" class="mt-4">
                        {{ __('profile.buttons.delete_account') }}
                    </x-buttons.danger-button>
                </div>
            </x-slot>
        </x-profile.profile-card>
    </div>

    {{-- Unlink Account Modal --}}
    <x-modals.modal wire:model="unlinkPlayerModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('admin.titles.users.unlink') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('admin.messages.users.unlink_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('unlinkPlayerModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="unlink">
                {{ __('profile.buttons.unlink_player') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Account Modal --}}
    <x-modals.modal wire:model="deleteAccountModal">
        <x-slot name="title"><div class="flex justify-center">{{ __('profile.titles.delete_account') }}</div></x-slot>
        <x-slot name="content">
            <p>{{ __('profile.messages.delete_account_confirmation') }}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteAccountModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyAccount">
                {{ __('profile.buttons.delete_account') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
