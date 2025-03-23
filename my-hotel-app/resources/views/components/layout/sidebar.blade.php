<aside class="bg-gray-800 text-white w-64 min-h-screen px-4 py-6 fixed left-0">
    <div class="flex items-center justify-center mb-8">
        <span class="text-2xl font-semibold">Hotel Inventory</span>
    </div>

    <nav class="space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Inventory Items -->
        <div x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg hover:bg-gray-700">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <span>Inventory</span>
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-2 space-y-2" style="display: none;">
                <a href="{{ route('inventory.items.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('inventory.items.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <span>All Items</span>
                </a>

                <a href="{{ route('inventory.categories.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('inventory.categories.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <span>Categories</span>
                </a>

                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
                    <span>Low Stock</span>
                </a>
            </div>
        </div>

        <!-- Stock Management -->
        <div x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 rounded-lg hover:bg-gray-700">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Stock</span>
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="pl-8 mt-2 space-y-2" style="display: none;">
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
                    <span>Stock In</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
                    <span>Stock Out</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
                    <span>Transfer</span>
                </a>
            </div>
        </div>

        <!-- Suppliers -->
        <a href="{{ route('inventory.suppliers.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Suppliers</span>
        </a>

        <!-- Purchase Orders -->
        <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span>Purchase Orders</span>
        </a>

        <!-- Locations -->
        <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Locations</span>
        </a>

        <!-- Reports -->
        <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Reports</span>
        </a>

        <!-- Audit Logs -->
        <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Audit Logs</span>
        </a>
    </nav>
</aside>
