@extends('layouts.app')

@section('header')
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Audit Logs') }}
            </h2>
        </div>
    </header>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-100 p-4 rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Audit Logging System</h1>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <!-- Debug section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200" style="min-height: 100px;">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Debug Information</h3>
                    <p>Count of logs: {{ $logs->count() }} of {{ $logs->total() }}</p>
                    <p>Current filters: 
                        @php
                            $activeFilters = [];
                            if(request('user_id')) {
                                $userName = $users->where('id', request('user_id'))->first()->name ?? 'Unknown User';
                                $activeFilters[] = "User: $userName";
                            }
                            if(request('action')) {
                                $activeFilters[] = "Action: " . ucfirst(request('action'));
                            }
                            if(request('table_name')) {
                                $activeFilters[] = "Table: " . ucwords(str_replace('_', ' ', request('table_name')));
                            }
                            if(request('date_from')) {
                                $activeFilters[] = "From: " . request('date_from');
                            }
                            if(request('date_to')) {
                                $activeFilters[] = "To: " . request('date_to');
                            }
                            echo !empty($activeFilters) ? implode(', ', $activeFilters) : 'None';
                        @endphp
                    </p>
                    @if(isset($error))
                        <p class="text-red-500">Error: {{ $error }}</p>
                    @endif
                </div>
            </div>
            
            <!-- Filters Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Filter Audit Logs</h3>
                    <form action="{{ route('audit-logs.index') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700">Action</label>
                                <select name="action" id="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Actions</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="table_name" class="block text-sm font-medium text-gray-700">Table</label>
                                <select name="table_name" id="table_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Tables</option>
                                    @foreach($tables as $table)
                                        <option value="{{ $table }}" {{ request('table_name') == $table ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $table)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">Clear</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Logs Table Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Logs Table -->
                    <div class="overflow-x-auto bg-white p-4 rounded-lg shadow" style="min-height: 200px;">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">Audit Logs</h3>
                        
                        @if($logs->isEmpty())
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No audit logs found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No data available with current filter settings.
                                </p>
                                @if(request()->hasAny(['user_id', 'action', 'table_name', 'date_from', 'date_to']))
                                    <div class="mt-6">
                                        <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Clear Filters
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                            Date & Time
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                            User
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                            Action
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                            Details
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($logs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b">
                                            @formatDateTime($log->created_at)
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b">
                                            @if ($log->user_id)
                                                <div class="text-sm font-medium text-gray-900">{{ $log->user_name }}</div>
                                                <div class="text-xs text-gray-500">{{ $log->user_email }}</div>
                                            @else
                                                <span class="text-sm text-gray-500">System</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-b">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ match($log->action) {
                                                'created' => 'bg-green-100 text-green-800',
                                                'updated' => 'bg-blue-100 text-blue-800',
                                                'deleted' => 'bg-red-100 text-red-800',
                                                'login' => 'bg-purple-100 text-purple-800',
                                                'logout' => 'bg-yellow-100 text-yellow-800',
                                                'status_changed' => 'bg-indigo-100 text-indigo-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            } }}">
                                                {{ match($log->action) {
                                                    'created' => 'Created',
                                                    'updated' => 'Updated',
                                                    'deleted' => 'Deleted',
                                                    'login' => 'Logged in',
                                                    'logout' => 'Logged out',
                                                    'status_changed' => 'Status Changed',
                                                    default => ucfirst($log->action)
                                                } }}
                                            </span>
                                            
                                            @if ($log->table_name && !in_array($log->action, ['login', 'logout']))
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ ucwords(str_replace('_', ' ', $log->table_name)) }} #{{ $log->record_id }}
                                            </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b">
                                            @if ($log->action === 'login')
                                                User logged into the system
                                            @elseif ($log->action === 'logout')
                                                User logged out of the system
                                            @elseif ($log->action === 'system')
                                                System event
                                            @elseif ($log->action === 'status_changed' && $log->table_name === 'purchase_orders')
                                                @php
                                                    $newStatus = $log->new_values['status'] ?? null;
                                                    $specificAction = $log->new_values['action'] ?? null;
                                                @endphp
                                                
                                                @if ($specificAction === 'marked_as_delivered')
                                                    Marked Purchase Order #{{ $log->record_id }} as Delivered
                                                @elseif ($specificAction === 'marked_as_canceled')
                                                    Marked Purchase Order #{{ $log->record_id }} as Canceled
                                                @else
                                                    Changed Purchase Order #{{ $log->record_id }} status to {{ ucfirst($newStatus) }}
                                                @endif
                                            @else
                                                {{ ucfirst($log->action) }} {{ ucwords(str_replace('_', ' ', $log->table_name)) }} record #{{ $log->record_id }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-b">
                                            <a href="{{ route('audit-logs.show', $log->id) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center p-1 rounded-full hover:bg-indigo-100" title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 