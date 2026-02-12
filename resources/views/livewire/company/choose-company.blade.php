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
                'label' => __('sidebar.company.choose'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('sidebar.company.choose') }}</x-containers.title>
        <p class="text-gray-600">{{ __('company.messages.choose_description') }}</p>
    </x-containers.main>

    <div class="grid grid-cols-3 gap-4 mt-4">
        @forelse ($companies as $company)
            <x-containers.main>
                <a href="{{ route('company.dashboard.render', ['companyId' => $company->id]) }}" class="text-lg font-semibold text-heading hover:text-blue-500">
                    {{ $company->name }}
                </a>
                
                <div class="grid grid-cols-2 gap-2">
                    {{-- Owner --}}
                    <div class="col-span-1">
                        <p class="text-sm text-gray-800 dark:text-gray-100">{{ __('company.labels.owner') }}: 
                            <span class="text-gray-600 dark:text-gray-400">{{ $company->owner->username ?? '-' }}</span>
                        </p>
                    </div>

                    {{-- Employee Count --}}
                    <div class="col-span-1">
                        <p class="text-sm text-gray-800 dark:text-gray-100">{{ __('company.labels.employees') }}: 
                            <span class="text-gray-600 dark:text-gray-400">{{ $company->employees->count() }}</span>
                        </p>
                    </div>
                </div>
            </x-containers.main>
        @empty
            <div class="col-span-3">
                <x-containers.main>
                    <p class="text-gray-600">{{ __('company.messages.no_companies') }}</p>
                </x-containers.main>
            </div>
        @endforelse
    </div>
</div>
