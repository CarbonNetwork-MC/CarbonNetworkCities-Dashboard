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
                'url'   => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => $company->name,
            ],
            [
                'url'   => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.dashboard'),
            ],
        ]" />
    </x-slot>

    <div class="grid grid-cols-3 gap-4">
        {{-- Stock Overview --}}
        <div class="col-span-1">
            <x-containers.main></x-containers.main>
        </div>

        {{-- Best Selling Products --}}
        <div class="col-span-1">
            <x-containers.main></x-containers.main>
        </div>

        {{-- Bank Accounts Overview --}}
        <div class="col-span-1">
            <x-containers.main></x-containers.main>
        </div>

        {{-- Employee Overview --}}
        <div class="col-span-1">
            <x-containers.main></x-containers.main>
        </div>

        {{-- Notifications --}}
        <div class="col-span-1">
            <x-containers.main></x-containers.main>
        </div>

        {{-- Empty --}}
        <div class="col-span-1">

        </div>
    </div>
</div>
