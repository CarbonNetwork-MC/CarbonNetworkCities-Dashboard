<div x-data="sidebarState()" x-init="init()" @keydown.window.escape="isOpenMobile && closeMobile()" class="relative">
    <!-- Mobile overlay -->
    <div x-show="isOpenMobile" x-transition.opacity @click="closeMobile()"
        class="fixed inset-0 z-40 bg-black/40 lg:hidden" aria-hidden="true"></div>

    <!-- Sidebar panel -->
    <aside :class="[
            (isOpenMobile || isDesktop) ? 'translate-x-0' : '-translate-x-full',
            isCollapsed ? 'lg:w-20' : 'lg:w-64'
        ]" 
        x-transition:enter="transition ease-out duration-200" 
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" 
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0" 
        x-transition:leave-end="-translate-x-full"
        class="fixed lg:sticky top-0 z-50 lg:z-20 lg:top-20 h-dvh shrink-0 bg-white/90 dark:bg-zinc-900/90 backdrop-blur border-r border-zinc-200 dark:border-zinc-800 p-3 lg:p-4 will-change-transform transition-all duration-200 flex flex-col overflow-hidden"
        aria-label="Sidebar"
    >

        <!-- Header / Brand + Collapse Toggle (desktop) -->
        <div class="flex items-center mb-2 border-b border-zinc-200 dark:border-zinc-800 pb-3" :class="isCollapsed ? 'flex-col justify-center' : 'justify-between'">
            <a href="{{ route('dashboard.render') }}" x-show="!isCollapsed" class="flex items-center gap-2">
                <img src="{{ $user->playerHead() }}" alt="Logo" class="h-8 w-8 object-cover" />
                <span class="font-semibold text-lg text-zinc-800 dark:text-zinc-100">{{ $user->player->username }}</span>
            </a>
            <button type="button"
                class="items-center justify-center rounded-xl p-2 text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white cursor-pointer"
                @click="toggleCollapse()" :aria-expanded="(!isCollapsed).toString()"
                :title="isCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                <i class="flex items-center fi"
                    :class="isCollapsed ? 'fi-tr-angle-double-small-right' : 'fi-tr-angle-double-small-left'"></i>
            </button>
        </div>

        <!-- Nav -->
        @if (!request()->routeIs('admin.*'))
            <nav class="space-y-2 flex-1 min-h-0">
                {{-- Dashboard --}}
                <x-sidebar.nav-item
                    :href="route('dashboard.render')"
                    :active="request()->routeIs('dashboard.render')"
                    icon="fi fi-rr-home"
                    :label="__('sidebar.dashboard')"
                />

                {{-- Company --}}
                @if ($selectedCompany)
                    <x-sidebar.nav-group :groupKey="'company'" wire:key="company" label="{{ __('sidebar.company.title') }}" icon="rr-building">
                        {{-- Dashboard --}}
                        <x-sidebar.nav-group-item
                            href="{{ route('company.dashboard.render', ['companyId' => $selectedCompany->id]) }}"
                            :active="request()->routeIs('company.dashboard.render')"
                            wire:key="company-dashboard"
                        >
                            {{ __('sidebar.company.dashboard') }}
                        </x-sidebar.nav-group-item>

                        {{-- Sell Docs --}}
                        <x-sidebar.nav-group-item
                            href="{{ route('company.sales.render', ['companyId' => $selectedCompany->id]) }}"
                            :active="request()->routeIs('company.sales.*')"
                            wire:key="company-sales"
                        >
                            {{ __('sidebar.company.sales') }}
                        </x-sidebar.nav-group-item>

                        {{-- Tips --}}
                        <x-sidebar.nav-group-item
                            href="{{ route('company.tips.render', ['companyId' => $selectedCompany->id]) }}"
                            :active="request()->routeIs('company.tips.*')"
                            wire:key="company-tips"
                        >
                            {{ __('sidebar.company.tips') }}
                        </x-sidebar.nav-group-item>

                        {{-- Inventory --}}
                        <x-sidebar.nav-group-item
                            href="{{ route('company.stock.render', ['companyId' => $selectedCompany->id]) }}"
                            :active="request()->routeIs('company.stock.*')"
                            wire:key="company-stock"
                        >
                            {{ __('sidebar.company.inventory') }}
                        </x-sidebar.nav-group-item>

                        {{-- Employees --}}
                        <x-sidebar.nav-group-item
                            href="{{ route('company.employees.render', ['companyId' => $selectedCompany->id]) }}"
                            :active="request()->routeIs('company.employees.*')"
                            wire:key="company-employees"
                        >
                            {{ __('sidebar.company.employees') }}
                        </x-sidebar.nav-group-item>

                        {{-- Bank Accounts --}}
                        @if ($isCompanyOwnerOrManager)
                            <x-sidebar.nav-group-item
                                href="{{ route('company.bank-accounts.render', ['companyId' => $selectedCompany->id]) }}"
                                :active="request()->routeIs('company.bank-accounts.*') || request()->routeIs('company.bank-account.*')"
                                wire:key="company-bank-accounts"
                            >
                                {{ __('sidebar.company.bank_accounts') }}
                            </x-sidebar.nav-group-item>
                        @endif

                        {{-- Salaries --}}
                        @if ($isCompanyOwnerOrManager)
                            <x-sidebar.nav-group-item
                                href="{{ route('company.salaries.render', ['companyId' => $selectedCompany->id]) }}"
                                :active="request()->routeIs('company.salaries.*')"
                                wire:key="company-salaries"
                            >
                                {{ __('sidebar.company.salaries') }}
                            </x-sidebar.nav-group-item>
                        @endif

                        {{-- Wholesale Orders --}}
                        @if ($isCompanyOwnerOrManager)
                            <x-sidebar.nav-group-item
                                href="{{ route('company.wholesale-orders.render', ['companyId' => $selectedCompany->id]) }}"
                                :active="request()->routeIs('company.wholesale-orders.*')"
                                wire:key="company-wholesale-orders"
                            >
                                {{ __('sidebar.company.wholesale_orders') }}
                            </x-sidebar.nav-group-item>
                        @endif

                        {{-- Archive --}}
                        @if ($isCompanyOwnerOrManager)
                            <x-sidebar.nav-group-item
                                href="{{ route('company.archive.render', ['companyId' => $selectedCompany->id]) }}"
                                :active="request()->routeIs('company.archive.*')"
                                wire:key="company-archive"
                            >
                                {{ __('sidebar.company.archive') }}
                            </x-sidebar.nav-group-item>
                        @endif

                        {{-- Company Settings --}}
                        @if ($isCompanyOwnerOrManager)
                            <x-sidebar.nav-group-item
                                href="{{ route('company.settings.render', ['companyId' => $selectedCompany->id]) }}"
                                :active="request()->routeIs('company.settings.*')"
                                wire:key="company-settings"
                            >
                                {{ __('sidebar.company.settings') }}
                            </x-sidebar.nav-group-item>
                        @endif
                    </x-sidebar.nav-group>
                @else
                    <x-sidebar.nav-item
                        :href="route('company.choose.render')"
                        :active="request()->routeIs('company.choose.render')"
                        icon="fi fi-rr-building"
                        :label="__('sidebar.company.title')"
                    />
                @endif
            </nav>
        @endif

        <!-- Admin Nav -->
        @if (request()->routeIs('admin.*') && $user->hasRole('Superadmin'))
            <nav class="space-y-2 flex-1 min-h-0 overflow-y-auto hide-scrollbar">
                {{-- Admin Dashboard --}}
                <x-sidebar.nav-item
                    :href="route('admin.dashboard.render')"
                    :active="request()->routeIs('admin.dashboard.*')"
                    icon="fi fi-rr-home"
                    :label="__('sidebar.dashboard')"
                />

                {{-- Bank Accounts --}}
                <x-sidebar.nav-item
                    :href="route('admin.bank-accounts.render')"
                    :active="request()->routeIs('admin.bank-accounts.*')"
                    icon="fi fi-tr-piggy-bank"
                    :label="__('sidebar.bank_accounts')"
                />

                {{-- City Regions --}}
                <x-sidebar.nav-item
                    :href="route('admin.city-regions.render')"
                    :active="request()->routeIs('admin.city-regions.*')"
                    icon="fi fi-rr-region-pin-alt"
                    :label="__('sidebar.city_regions')"
                />

                {{-- CoC --}}
                <x-sidebar.nav-item
                    :href="route('admin.coc.render')"
                    :active="request()->routeIs('admin.coc.*')"
                    icon="fi fi-rr-book"
                    :label="__('sidebar.coc')"
                />

                {{-- Companies --}}
                <x-sidebar.nav-item
                    :href="route('admin.companies.render')"
                    :active="request()->routeIs('admin.companies.*')"
                    icon="fi fi-rr-building"
                    :label="__('sidebar.companies')"
                />

                {{-- Countries --}}
                <x-sidebar.nav-item
                    :href="route('admin.countries.render')"
                    :active="request()->routeIs('admin.countries.*')"
                    icon="fi fi-rr-flag"
                    :label="__('sidebar.countries')"
                />

                {{-- Itemsmenu --}}
                <x-sidebar.nav-item
                    :href="route('admin.itemsmenu.render')"
                    :active="request()->routeIs('admin.itemsmenu.*')"
                    icon="fi fi-br-grid"
                    :label="__('sidebar.itemsmenu')"
                />

                {{-- Languages --}}
                <x-sidebar.nav-item
                    :href="route('admin.languages.render')"
                    :active="request()->routeIs('admin.languages.*')"
                    icon="fi fi-tr-language-exchange"
                    :label="__('sidebar.languages')"
                />

                {{-- Players --}}
                <x-sidebar.nav-item
                    :href="route('admin.players.render')"
                    :active="request()->routeIs('admin.players.*')"
                    icon="fi fi-rr-user"
                    :label="__('sidebar.players')"
                />

                {{-- Plots --}}
                <x-sidebar.nav-item
                    :href="route('admin.plots.render')"
                    :active="request()->routeIs('admin.plots.*')"
                    icon="fi fi-rr-land-layer-location"
                    label="Plots"
                />
                    
                {{-- PIN Consoles --}}
                <x-sidebar.nav-item
                    :href="route('admin.pin-consoles.render')"
                    :active="request()->routeIs('admin.pin-consoles.*')"
                    icon="fi fi-rr-payment-pos"
                    :label="__('sidebar.pin_consoles')"
                />

                {{-- Roles & Permissions --}}
                @if ($user->can('manage_permissions'))
                <x-sidebar.nav-item
                    :href="route('admin.roles-perms.render')"
                    :active="request()->routeIs('admin.roles-perms.*')"
                    icon="fi fi-rr-shield-check"
                    :label="__('sidebar.roles_perms')"
                />
                @endif

                {{-- Users --}}
                <x-sidebar.nav-item
                    :href="route('admin.users.render')"
                    :active="request()->routeIs('admin.users.*')"
                    icon="fi fi-rr-users"
                    :label="__('sidebar.users')"
                />

                {{-- Wholesale Items --}}
                <x-sidebar.nav-item
                    :href="route('admin.wholesale-items.render')"
                    :active="request()->routeIs('admin.wholesale-items.*')"
                    icon="fi fi-rr-shelves"
                    :label="__('sidebar.wholesale_items')"
                />
            </nav>
        @endif

        <!-- Footer -->
        <div class="mt-auto">
            @if ($user->hasRole('Superadmin'))
            {{-- Admin Section --}}
            <x-sidebar.nav-divider />

            <div class="mb-2">
                @if (request()->routeIs('admin.*'))
                    <x-sidebar.nav-item
                        :href="route('dashboard.render')"
                        :active="false"
                        icon="fi fi-rr-arrow-small-left"
                        :label="__('sidebar.back_to_dashboard')"
                    />
                @else
                    <x-sidebar.nav-item
                        :href="route('admin.dashboard.render')"
                        :active="request()->routeIs('admin.dashboard.*')"
                        icon="fi fi-rr-admin-alt"
                        :label="__('sidebar.management')"
                    />
                @endif
            </div>
            @endif

            <div class="border-t border-zinc-200 dark:border-zinc-800 pt-3">
                <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                    <!-- Profile button -->
                    <button type="button" @click="open = !open" :title="isCollapsed ? '{{ Auth::user()->name ?? 'Account' }}' : null"
                        class="w-full rounded-xl px-2 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 flex items-center gap-3 cursor-pointer"
                    >

                        <!-- Avatar -->
                        <img src="{{ Auth::user()->profile_photo_url
                            ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'U') . '&background=16a34a&color=ffffff' }}"
                            alt="{{ Auth::user()->name ?? 'User' }}" class="h-8 w-8 rounded-xl object-cover" />

                        <!-- Name / email (hidden when collapsed) -->
                        <div x-show="!isCollapsed" class="min-w-0 text-left">
                            <p class="text-sm font-medium text-zinc-800 dark:text-zinc-100 truncate">
                                {{ Auth::user()->name ?? 'Your Name' }}
                            </p>
                            <p class="text-xs text-zinc-500 truncate">
                                {{ Auth::user()->email ?? 'you@example.com' }}
                            </p>
                        </div>

                        <!-- Chevron (hidden when collapsed) -->
                        <svg x-show="!isCollapsed" :class="{ 'rotate-0': open, 'rotate-180': !open }" class="ml-auto h-4 w-4 text-zinc-500 transition-transform" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true"
                        >
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown (opens UP) -->
                    <div x-show="open" x-transition.origin-bottom @click.outside="open = false" class="absolute left-0 right-0 z-50 bottom-12 mb-2"
                        :class="isCollapsed ? 'left-1/2 -translate-x-1/2 w-56' : 'left-0 right-0'">

                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-xl overflow-hidden">
                            <!-- Dark mode switch -->
                            <label class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer select-none hover:bg-zinc-100 dark:hover:bg-zinc-800"
                                role="switch" :aria-checked="dark.toString()"
                            >
                                <i class="fi" :class="dark ? 'fi-rr-moon' : 'fi-rr-sun'"></i>
                                <span class="text-zinc-700 dark:text-zinc-200">Dark mode</span>

                                <input type="checkbox" x-model="dark" class="sr-only peer" />

                                <span class="ml-auto relative inline-flex h-5 w-9 items-center rounded-full transition-colors duration-200"
                                    :class="dark ? 'bg-zinc-700' : 'bg-zinc-300'"
                                >
                                    <span class="h-4 w-4 bg-white rounded-full shadow transform transition-transform duration-200"
                                        :class="dark ? 'translate-x-5' : 'translate-x-1'"></span>
                                </span>
                            </label>

                            <div class="border-t border-zinc-200 dark:border-zinc-800"></div>

                            <a href="{{ route('profile.render') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                <i class="fi fi-rr-user"></i>
                                <span>Profile</span>
                            </a>

                            <div class="border-t border-zinc-200 dark:border-zinc-800"></div>

                            <a href="" class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                <i class="fi fi-rr-settings"></i>
                                <span>Settings</span>
                            </a>

                            <div class="border-t border-zinc-200 dark:border-zinc-800"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 cursor-pointer">
                                    <i class="fi fi-rr-exit"></i>
                                    <span>Log out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile top bar -->
    <div
        class="lg:hidden sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800">
        <div class="flex items-center gap-2 p-3">
            <button @click="openMobile()"
                class="inline-flex items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-800 p-2 text-zinc-700 dark:text-zinc-200">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                    <path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z" />
                </svg>
            </button>
            <span class="font-semibold text-zinc-800 dark:text-zinc-100">Menu</span>
        </div>
    </div>
</div>

<script>
    function sidebarState() {
        const STORAGE_KEY = 'sidebar:collapsed';
        const GROUPS_KEY = 'sidebar:openGroups';
        return {
            isDesktop: window.matchMedia('(min-width: 1024px)').matches,
            isOpenMobile: false,
            isCollapsed: false,
            openGroups: new Set(),
            init() {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    this.isCollapsed = saved === '1';

                    const g = JSON.parse(localStorage.getItem(GROUPS_KEY) || '[]');
                    this.openGroups = new Set(g);

                    const mq = window.matchMedia('(min-width: 1024px)');
                    const sync = () => { this.isDesktop = mq.matches; if (!this.isDesktop) this.isCollapsed = false; };
                    mq.addEventListener('change', sync);
                    sync();
                },
                persist() {
                    localStorage.setItem(STORAGE_KEY, this.isCollapsed ? '1' : '0');
                    localStorage.setItem(GROUPS_KEY, JSON.stringify(Array.from(this.openGroups)));
                },
                toggleCollapse() {
                    this.isCollapsed = !this.isCollapsed;
                    localStorage.setItem(STORAGE_KEY, this.isCollapsed ? '1' : '0');
                },
                expandFromIcon() {
                    this.isCollapsed = false;
                    this.persist();
                },
                openMobile() { this.isOpenMobile = true; },
                closeMobile() { this.isOpenMobile = false; },
                toggleGroup(key) {
                    if (this.openGroups.has(key)) this.openGroups.delete(key); else this.openGroups.add(key);
                    localStorage.setItem(GROUPS_KEY, JSON.stringify(Array.from(this.openGroups)));
                },
                isGroupOpen(key) { return this.openGroups.has(key); },
            navLinkClass(active) {
                return [
                    'group items-center gap-3 rounded-xl px-2 py-2 text-sm text-black dark:text-white',
                    active && !this.isCollapsed ? 'bg-green-500/20' : 'hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800',
                    this.isCollapsed ? '' : 'flex'
                ].join(' ');
            },
            navSubLinkClass(active) {
                return [
                    'ml-11 block rounded-lg px-2 py-1.5 text-sm text-zinc-700',
                    active ? 'bg-green-500/20 dark:text-zinc-300' : 'hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800'
                ].join(' ');
            },
        }
    }
</script>