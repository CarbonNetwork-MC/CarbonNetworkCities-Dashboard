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
                'url'   => route('company.choose.render'),
                'label' => __('sidebar.company.title'),
            ],
            [
                'url' => '',
                'label' => $company->name,
            ],
            [
                'url'   => route('company.stock.render', ['companyId' => $company->id]),
                'label' => __('company.titles.inventory'),
            ],
            [
                'url'   => route('company.stock.render', ['companyId' => $company->id]),
                'label' => __('company.titles.update_inventory'),
            ],
        ]" />
    </x-slot>

    <div class="flex justify-center">
        <div class="w-[50%]">
            <x-containers.main>
                <x-containers.title>{{ __('company.titles.update_inventory') }}</x-containers.title>

                <div class="mt-6">
                    <div class="grid grid-cols-2 gap-y-4">
                        @forelse ($company->items as $item)
                            <div class="col-span-1 flex items-center">
                                <p class="text-black dark:text-white">{{ $item->item->name }}</p>
                            </div>

                            <div class="col-span-1">
                                <x-forms.number-input class="w-full" wire:model="stockUpdates.{{ $item->id }}" />
                            </div>
                        @empty
                            <p class="col-span-2 text-center text-gray-500">{{ __('company.messages.no_items') }}</p>
                        @endforelse
                    </div>

                    <div class="flex justify-end mt-4">
                        <x-buttons.primary-button wire:click="saveStock">
                            {{ __('general.buttons.save') }}
                        </x-buttons.primary-button>
                    </div>
                </div>
            </x-containers.main>
        </div>
    </div>
</div>
