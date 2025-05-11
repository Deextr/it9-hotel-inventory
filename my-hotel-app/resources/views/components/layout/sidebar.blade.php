<aside x-data="{ 
         get collapsed() { return $store.sidebar.collapsed },
         toggleSidebar() { $store.sidebar.toggle() }
     }" 
     :class="{'w-64': !collapsed, 'w-16': collapsed}"
     class="bg-gray-800 text-white min-h-screen px-2 py-6 fixed left-0 transition-all duration-300 z-10">
    
    <!-- Toggle Button -->
    <button @click="toggleSidebar()" class="absolute -right-3 top-10 bg-gray-800 text-white p-1 rounded-full border border-gray-600 focus:outline-none">
        <svg :class="{'rotate-180': collapsed}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>
    
    <div class="flex items-center justify-center mb-8" :class="{'justify-center': collapsed, 'justify-start px-2': !collapsed}">
        <svg class="w-8 h-8" :class="{'mr-0': collapsed, 'mr-2': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <span x-show="!collapsed" class="text-2xl font-semibold transition-opacity duration-300">Hotel Inventory</span>
    </div>

    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
           :class="{'justify-center px-2': collapsed, 'px-4': !collapsed}">
            <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span x-show="!collapsed" class="transition-opacity duration-300">Dashboard</span>
        </a>

        <!-- Inventory Items -->
        <div x-data="{ open: {{ request()->routeIs('inventory.items.*') || request()->routeIs('inventory.categories.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white w-full py-2"
                   :class="{'justify-center px-2': collapsed, 'justify-between px-4': !collapsed}">
                <div class="flex items-center">
                    <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <span x-show="!collapsed" class="transition-opacity duration-300">Inventory</span>
                </div>
                <svg x-show="!collapsed" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="(!collapsed && open) || (collapsed && $el.matches(':hover'))" 
                 :class="{'absolute left-16 top-auto mt-0 bg-gray-800 rounded-md shadow-lg z-10 py-1 px-2 min-w-[200px]': collapsed, 'pl-8 mt-2': !collapsed}" 
                 class="space-y-1" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100">
                <a href="{{ route('inventory.items.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.items.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>All Items</span>
                </a>
                <a href="{{ route('inventory.categories.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.categories.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Categories</span>
                </a>
            </div>
        </div>

        <!-- Stock Management -->
        <div x-data="{ open: {{ request()->routeIs('inventory.view*') || request()->routeIs('inventory.stock.*') || request()->routeIs('inventory.transfers.*') || request()->routeIs('inventory.pullouts.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white w-full py-2"
                   :class="{'justify-center px-2': collapsed, 'justify-between px-4': !collapsed}">
                <div class="flex items-center">
                    <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span x-show="!collapsed" class="transition-opacity duration-300">Stock</span>
                </div>
                <svg x-show="!collapsed" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="(!collapsed && open) || (collapsed && $el.matches(':hover'))" 
                 :class="{'absolute left-16 top-auto mt-0 bg-gray-800 rounded-md shadow-lg z-10 py-1 px-2 min-w-[200px]': collapsed, 'pl-8 mt-2': !collapsed}" 
                 class="space-y-1" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100">
                <a href="{{ route('inventory.view') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.view') && !request()->query('filter') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>View Stock</span>
                </a>
                <a href="{{ route('inventory.stock.out') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.stock.out') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Assign to Location</span>
                </a>
                <a href="{{ route('inventory.stock.bulk-out') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.stock.bulk-out') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Bulk Assignment</span>
                </a>
                <a href="{{ route('inventory.transfers.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.transfers.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Item Transfers</span>
                </a>
                <a href="{{ route('inventory.pullouts.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.pullouts.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Item Pullouts</span>
                </a>
            </div>
        </div>

        <!-- Suppliers -->
        <a href="{{ route('inventory.suppliers.index') }}" class="flex items-center py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.suppliers.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
           :class="{'justify-center px-2': collapsed, 'px-4': !collapsed}">
            <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span x-show="!collapsed" class="transition-opacity duration-300">Suppliers</span>
        </a>

        <!-- Purchase Orders -->
        <a href="{{ route('inventory.purchase_orders.index') }}" class="flex items-center py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.purchase_orders.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
           :class="{'justify-center px-2': collapsed, 'px-4': !collapsed}">
            <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span x-show="!collapsed" class="transition-opacity duration-300">Purchase Orders</span>
        </a>

        <!-- Locations -->
        <div x-data="{ open: {{ request()->routeIs('locations.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white w-full py-2"
                   :class="{'justify-center px-2': collapsed, 'justify-between px-4': !collapsed}">
                <div class="flex items-center">
                    <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="!collapsed" class="transition-opacity duration-300">Locations</span>
                </div>
                <svg x-show="!collapsed" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="(!collapsed && open) || (collapsed && $el.matches(':hover'))" 
                 :class="{'absolute left-16 top-auto mt-0 bg-gray-800 rounded-md shadow-lg z-10 py-1 px-2 min-w-[200px]': collapsed, 'pl-8 mt-2': !collapsed}" 
                 class="space-y-1" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100">
                <a href="{{ route('locations.create') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('locations.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Create Location</span>
                </a>
                <a href="{{ route('locations.create-batch') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('locations.create-batch') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>Create Multiple</span>
                </a>
                <a href="{{ route('locations.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('locations.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <span>View Locations</span>
                </a>
            </div>
        </div>

        <!-- Reports -->
        <a href="{{ route('inventory.reports.index') }}" class="flex items-center py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.reports.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
           :class="{'justify-center px-2': collapsed, 'px-4': !collapsed}">
            <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span x-show="!collapsed" class="transition-opacity duration-300">Reports</span>
        </a>

        <!-- Audit Logs -->
        <a href="{{ route('audit-logs.index') }}" class="flex items-center py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('audit-logs.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
           :class="{'justify-center px-2': collapsed, 'px-4': !collapsed}">
            <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-show="!collapsed" class="transition-opacity duration-300">Audit Logs</span>
        </a>
    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-2 pb-4">
        <div class="border-t border-gray-700 pt-4">
            <div x-data="{ dropdownOpen: false }" class="relative">
                <!-- User button -->
                <button @click="dropdownOpen = !dropdownOpen" 
                        type="button"
                        class="w-full flex items-center py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white"
                        :class="{'justify-center px-2': collapsed, 'justify-between px-4': !collapsed}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5" :class="{'mr-0': collapsed, 'mr-3': !collapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span x-show="!collapsed" class="transition-opacity duration-300">{{ Auth::user()->name }}</span>
                    </div>
                    <svg x-show="!collapsed" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Dropdown Menu for Collapsed Sidebar -->
                <div x-show="collapsed && dropdownOpen" 
                     @click.away="dropdownOpen = false"
                     class="absolute left-16 top-0 bg-gray-800 rounded-md shadow-lg z-50 py-1 min-w-[200px]"
                     style="transform: translateY(-30px);">
                    
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-t-md">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('My Profile') }}
                        </div>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-b-md">
                            <div class="flex items-center text-red-400 hover:text-red-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Log Out') }}
                            </div>
                        </button>
                    </form>
                </div>
                
                <!-- Dropdown Menu for Expanded Sidebar -->
                <div x-show="!collapsed && dropdownOpen" 
                     @click.away="dropdownOpen = false"
                     class="absolute right-0 bottom-12 bg-gray-800 rounded-md shadow-lg z-50 py-1 w-full">
                    
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('My Profile') }}
                        </div>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                            <div class="flex items-center text-red-400 hover:text-red-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('Log Out') }}
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
