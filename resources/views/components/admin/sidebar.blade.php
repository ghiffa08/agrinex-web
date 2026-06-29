@php
    use App\Helpers\MenuHelper;
    $menuGroups = MenuHelper::getMenuGroups();
    $currentPath = request()->path();
@endphp

<aside id="sidebar"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-[99999] border-r border-gray-200"
    x-data="{
        openSubmenus: {},
        currentPath: window.location.pathname,
        init() {
            this.initializeActiveMenus();
            
            window.addEventListener('spa:navigated', () => {
                this.currentPath = window.location.pathname;
                this.initializeActiveMenus();
            });
        },
        initializeActiveMenus() {
            const current = this.currentPath;
            this.openSubmenus = {};
            @foreach ($menuGroups as $groupIndex => $menuGroup)
                @foreach ($menuGroup['items'] as $itemIndex => $item)
                    @if (isset($item['subItems']))
                        @foreach ($item['subItems'] as $subItem)
                            if (current === '{{ $subItem['path'] }}' || 
                                current.replace(/^\//, '') === '{{ ltrim($subItem['path'], '/') }}') {
                                this.openSubmenus['{{ $groupIndex }}-{{ $itemIndex }}'] = true;
                            }
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        },
        toggleSubmenu(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            const newState = !this.openSubmenus[key];
            if (newState) {
                this.openSubmenus = {};
            }
            this.openSubmenus[key] = newState;
        },
        isSubmenuOpen(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            return this.openSubmenus[key] || false;
        },
        isActive(path) {
            const current = this.currentPath;
            return current === path || current.replace(/^\//, '') === path.replace(/^\//, '');
        }
    }"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    {{-- Logo Section --}}
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
        'xl:justify-center' : 'justify-start'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            {{-- Full logo (shown when expanded) --}}
            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="flex items-center gap-2">
                <div class="w-9 h-9 bg-brand-500 text-white rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Agri<span class="text-brand-500">Nex</span>
                </span>
            </span>

            {{-- Icon only (when collapsed) --}}
            <div x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                class="w-9 h-9 bg-brand-500 text-white rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </a>
    </div>

    {{-- Navigation Menu --}}
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                @foreach ($menuGroups as $groupIndex => $menuGroup)
                    <div>
                        {{-- Menu Group Title --}}
                        <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                            'lg:justify-center' : 'justify-start'">
                            <template
                                x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                            <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                                        fill="currentColor" />
                                </svg>
                            </template>
                        </h2>

                        {{-- Menu Items --}}
                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $itemIndex => $item)
                                <li>
                                    @if (isset($item['subItems']))
                                        {{-- Menu Item with Submenu --}}
                                        <button @click="toggleSubmenu({{ $groupIndex }}, {{ $itemIndex }})"
                                            class="menu-item group w-full"
                                            :class="[
                                                isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ?
                                                'menu-item-active' : 'menu-item-inactive',
                                                !$store.sidebar.isExpanded && !$store.sidebar.isHovered ?
                                                'xl:justify-center' : 'xl:justify-start'
                                            ]">
                                            <span :class="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ?
                                                    'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                                {!! MenuHelper::getIconSvg($item['icon']) !!}
                                            </span>
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="menu-item-text flex items-center gap-2">
                                                {{ $item['name'] }}
                                            </span>
                                            <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="ml-auto w-5 h-5 transition-transform duration-200"
                                                :class="{
                                                    'rotate-180 text-brand-500': isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }})
                                                }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div x-show="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)">
                                            <ul class="mt-2 space-y-1 ml-9">
                                                @foreach ($item['subItems'] as $subItem)
                                                    <li>
                                                        <a href="{{ $subItem['path'] }}" class="menu-dropdown-item relative"
                                                            :class="isActive('{{ $subItem['path'] }}') ?
                                                                'menu-dropdown-item-active' :
                                                                'menu-dropdown-item-inactive'">
                                                            <span x-show="isActive('{{ $subItem['path'] }}')" 
                                                                  class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-4 bg-brand-500 rounded-r-full"
                                                                  x-transition:enter="transition ease-out duration-350"
                                                                  x-transition:enter-start="opacity-0 scale-y-50"
                                                                  x-transition:enter-end="opacity-100 scale-y-100">
                                                            </span>
                                                            {{ $subItem['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        {{-- Simple Menu Item --}}
                                        <a href="{{ $item['path'] }}" class="menu-item group relative"
                                            :class="[
                                                isActive('{{ $item['path'] }}') ? 'menu-item-active' :
                                                'menu-item-inactive',
                                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                                'xl:justify-center' :
                                                'justify-start'
                                            ]">
                                            <!-- Brand-aligned active indicator line on the left -->
                                            <span x-show="isActive('{{ $item['path'] }}')" 
                                                  class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-brand-500 rounded-r-full"
                                                  x-transition:enter="transition ease-out duration-350"
                                                  x-transition:enter-start="opacity-0 scale-y-50"
                                                  x-transition:enter-end="opacity-100 scale-y-100">
                                            </span>
                                            <span
                                                :class="isActive('{{ $item['path'] }}') ? 'menu-item-icon-active' :
                                                    'menu-item-icon-inactive'">
                                                {!! MenuHelper::getIconSvg($item['icon']) !!}
                                            </span>
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="menu-item-text">
                                                {{ $item['name'] }}
                                            </span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>

        {{-- Sidebar Widget --}}
        <div x-data x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
            x-transition class="mt-auto pb-6">
            <div class="rounded-2xl bg-brand-50 dark:bg-brand-500/[0.12] p-5 text-center">
                <div class="w-10 h-10 rounded-full bg-brand-500 text-white flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90">AgriNex v2.0</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">System operational</p>
            </div>
        </div>
    </div>
</aside>
