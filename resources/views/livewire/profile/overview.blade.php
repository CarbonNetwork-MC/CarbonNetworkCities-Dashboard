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

    <x-containers.main>
        <x-containers.title>{{ __('sidebar.profile') }}</x-containers.title>

        <div class="mt-4">
            <div class="grid grid-cols-5 gap-4">
                {{-- Profile Image --}}
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
                        helper="{{ __('profile.placeholders.image-upload-helper') }}"
                    />
                </div>

                <div class="col-span-4"></div>

                {{-- Username --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('profile.labels.username') }}" wire:model="username" required />
                </div>

                <div class="col-span-4"></div>
    
                {{-- Email --}}
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
        </div>
    </x-containers.main>
</div>
