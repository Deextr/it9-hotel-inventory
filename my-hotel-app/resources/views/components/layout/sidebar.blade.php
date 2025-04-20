<aside class="bg-gray-800 text-white w-64 min-h-screen px-4 py-6 fixed left-0">
    <div class="flex items-center justify-center mb-8">
        <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <span class="text-2xl font-semibold">Hotel Inventory</span>
    </div>

    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Inventory Items -->
        <div x-data="{ open: {{ request()->routeIs('inventory.items.*') || request()->routeIs('inventory.categories.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <span>Inventory</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-2 space-y-1" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100">
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
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Stock</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-2 space-y-1" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100">
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
        <a href="{{ route('inventory.suppliers.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.suppliers.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Suppliers</span>
        </a>

        <!-- Purchase Orders -->
        <a href="{{ route('inventory.purchase_orders.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.purchase_orders.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span>Purchase Orders</span>
        </a>

        <!-- Locations -->
        <div x-data="{ open: {{ request()->routeIs('locations.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Locations</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-2 space-y-1" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100">
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
        <a href="{{ route('inventory.reports.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('inventory.reports.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Reports</span>
        </a>

        <!-- Audit Logs -->
        <a href="{{ route('audit-logs.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('audit-logs.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Audit Logs</span>
        </a>
    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-4">
        <div class="border-t border-gray-700 pt-4">
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profile</span>
            </a>
        </div>
    </div>
</aside>
