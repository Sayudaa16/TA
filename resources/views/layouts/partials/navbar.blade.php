@php
    $currentRoute = Route::currentRouteName()
@endphp

{{-- Modern Navigation Bar --}}
<nav class="fixed top-0 z-50 w-full bg-white/80 backdrop-blur-md border-b border-gray-200 dark:bg-gray-800/80 dark:border-gray-700">
    <div class="px-4 py-2.5 lg:px-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 transition-all duration-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>
                <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('THREAT.png') }}" class="h-20 max-w-full transition-transform duration-200 hover:scale-105" alt="Logo" />
                    <div class="overflow-hidden whitespace-nowrap">
                        <p><h3><B>THREAT INTELLIGENCE</B></h3></p>
                    </div>
                </a>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <button type="button"
                        class="flex text-sm bg-gray-800 rounded-full ring-4 ring-gray-800/30 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 transition-all duration-200"
                        aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                    </button>

                    <div class="absolute right-0 z-50 hidden mt-2 text-base bg-white border border-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600 min-w-[200px]"
                        id="dropdown-user">
                        <ul class="py-2" role="none">
                            <li>
                                <a href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200"
                                    role="menuitem">
                                    Sign out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- Modern Sidebar --}}
<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform duration-300 ease-in-out -translate-x-full bg-white/80 backdrop-blur-md border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800/80 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-transparent mt-7">
        <ul class="space-y-2 font-medium">
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 transition-all duration-200 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-dashboard" data-collapse-toggle="dropdown-dashboard">
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap font-thin text-sm">Dashboard</span>
                    <svg class="w-3 h-3 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-dashboard" class="hidden py-2 space-y-1">
                    <li>
                        <a href="{{ route('monitor.index') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 font-thin text-sm transition-all duration-200 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $currentRoute === 'monitor.index' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('domains.index') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 font-thin text-sm transition-all duration-200 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $currentRoute === 'domains.index' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            Domain Monitor
                        </a>
                    </li>
                </ul>
            </li>

            {{-- CVE Database Section --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 transition-all duration-200 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-cve" data-collapse-toggle="dropdown-cve">
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap font-thin text-sm">CVE</span>
                    <svg class="w-3 h-3 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-cve" class="hidden py-2 space-y-1">
                    <li>
                        <a href="{{ route('opencve.index') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 font-thin text-sm transition-all duration-200 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $currentRoute === 'opencve.index' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            CVE Dashboard
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Threat Actor --}}
            <li>
                <a href="{{ route('otx') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group transition-all duration-200 {{ $currentRoute === 'otx' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    <span class="flex-1 ms-3 font-thin text-sm whitespace-nowrap">OTX</span>
                </a>
            </li>

            {{-- Web Scraping OSINT Section --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 transition-all duration-200 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-news" data-collapse-toggle="dropdown-news">
                    <span class="flex-1 ms-3 text-left rtl:text-right font-thin text-sm whitespace-nowrap">News</span>
                    <svg class="w-3 h-3 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-news" class="hidden py-2 space-y-1">
                    <li>
                        <a href="{{ route('hackernews') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 font-thin text-sm transition-all duration-200 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $currentRoute === 'hackernews' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            TheHacker News
                        </a>
                    </li>
                </ul>
            </li>

            {{-- User Management Section --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-gray-900 transition-all duration-200 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                    aria-controls="dropdown-users" data-collapse-toggle="dropdown-users">
                    <span class="flex-1 ms-3 text-left rtl:text-right font-thin text-sm whitespace-nowrap">User Management</span>
                    <svg class="w-3 h-3 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <ul id="dropdown-users" class="hidden py-2 space-y-1">
                    <li>
                        <a href="{{ route('user.management') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 font-thin text-sm transition-all duration-200 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $currentRoute === 'user.management' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            Create User
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login-logs') }}"
                            class="flex items-center w-full p-2 text-sm text-gray-900 font-thin text-sm transition-all duration-200 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{ $currentRoute === 'login-logs' ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            Login Logs
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Enhanced dropdown functionality
    const dropdownButtons = document.querySelectorAll("[data-collapse-toggle]");
    const dropdownMenus = document.querySelectorAll("[id^='dropdown-']");

    // Add smooth transition classes to all dropdown menus
    dropdownMenus.forEach(menu => {
        menu.classList.add('transition-all', 'duration-300', 'ease-in-out');
    });

    dropdownButtons.forEach(button => {
        button.addEventListener("click", function () {
            const targetId = this.getAttribute("aria-controls");
            const targetMenu = document.getElementById(targetId);
            const arrow = this.querySelector('svg:last-child');

            // Close other dropdowns
            dropdownMenus.forEach(menu => {
                if (menu !== targetMenu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    const otherButton = document.querySelector(`[aria-controls="${menu.id}"]`);
                    const otherArrow = otherButton.querySelector('svg:last-child');
                    otherArrow.style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current dropdown
            targetMenu.classList.toggle('hidden');

            // Rotate arrow
            if (targetMenu.classList.contains('hidden')) {
                arrow.style.transform = 'rotate(0deg)';
                localStorage.removeItem("activeDropdown");
            } else {
                arrow.style.transform = 'rotate(180deg)';
                localStorage.setItem("activeDropdown", targetId);
            }
        });
    });

    // Restore active dropdown state
    const activeDropdownId = localStorage.getItem("activeDropdown");
    if (activeDropdownId) {
        const activeDropdown = document.getElementById(activeDropdownId);
        const activeButton = document.querySelector(`[aria-controls="${activeDropdownId}"]`);
        if (activeDropdown && activeButton) {
            activeDropdown.classList.remove('hidden');
            const arrow = activeButton.querySelector('svg:last-child');
            arrow.style.transform = 'rotate(180deg)';
        }
    }
});
</script>
