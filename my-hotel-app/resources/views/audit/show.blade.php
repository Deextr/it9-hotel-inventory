@extends('layouts.app')

@section('header')
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Audit Log Details') }}
                </h2>
                <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    &larr; Back to List
                </a>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Date & Time</h3>
                                <p class="mt-1 text-lg text-gray-900">@formatDateTime($auditLog->created_at)</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">User</h3>
                                <p class="mt-1 text-lg text-gray-900">
                                    @if ($auditLog->user_id)
                                        {{ $auditLog->user_name }}
                                        <span class="text-sm text-gray-500">({{ $auditLog->user_email }})</span>
                                    @else
                                        System
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Action</h3>
                                <p class="mt-1">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ match($auditLog->action) {
                                        'created' => 'bg-green-100 text-green-800',
                                        'updated' => 'bg-blue-100 text-blue-800',
                                        'deleted' => 'bg-red-100 text-red-800',
                                        'login' => 'bg-purple-100 text-purple-800',
                                        'logout' => 'bg-yellow-100 text-yellow-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    } }}">
                                        {{ match($auditLog->action) {
                                            'created' => 'Created',
                                            'updated' => 'Updated',
                                            'deleted' => 'Deleted',
                                            'login' => 'Logged in',
                                            'logout' => 'Logged out',
                                            default => ucfirst($auditLog->action)
                                        } }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Action Information</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                            <div class="px-4 py-5 sm:p-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Action</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ match($auditLog->action) {
                                            'created' => 'Created',
                                            'updated' => 'Updated',
                                            'deleted' => 'Deleted',
                                            'login' => 'Logged in',
                                            'logout' => 'Logged out',
                                            default => ucfirst($auditLog->action)
                                        } }}</dd>
                                    </div>
                                    
                                    @if ($auditLog->table_name && !in_array($auditLog->action, ['login', 'logout']))
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Table</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $auditLog->table_name)) }}</dd>
                                    </div>
                                    
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Record ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">#{{ $auditLog->record_id }}</dd>
                                    </div>
                                    @endif

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            @if ($auditLog->action === 'login')
                                                User logged into the system
                                            @elseif ($auditLog->action === 'logout')
                                                User logged out of the system
                                            @elseif ($auditLog->action === 'system')
                                                System event
                                            @else
                                                {{ ucfirst($auditLog->action) }} {{ ucwords(str_replace('_', ' ', $auditLog->table_name)) }} record #{{ $auditLog->record_id }}
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Changes -->
                    @php
                        $hasChanges = false;
                        
                        if ($auditLog->action === 'created' && !empty($auditLog->new_values)) {
                            $hasChanges = true;
                        } elseif ($auditLog->action === 'deleted' && !empty($auditLog->old_values)) {
                            $hasChanges = true;
                        } elseif ($auditLog->action === 'updated' && !empty($auditLog->old_values) && !empty($auditLog->new_values)) {
                            $hasChanges = true;
                        }
                    @endphp
                    
                    @if ($hasChanges && !in_array($auditLog->action, ['login', 'logout', 'system']))
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Changes</h3>
                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                                        @if ($auditLog->action === 'updated')
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Before</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">After</th>
                                        @else
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if ($auditLog->action === 'updated')
                                        @foreach ((array) $auditLog->new_values as $field => $newValue)
                                        @php
                                            $oldValue = isset($auditLog->old_values->$field) ? $auditLog->old_values->$field : null;
                                            if ($oldValue === $newValue) continue;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-pre-wrap">
                                                @if (is_object($oldValue) || is_array($oldValue))
                                                    <pre class="text-xs">{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $oldValue }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-pre-wrap">
                                                @if (is_object($newValue) || is_array($newValue))
                                                    <pre class="text-xs">{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $newValue }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @elseif ($auditLog->action === 'created')
                                        @foreach ((array) $auditLog->new_values as $field => $value)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-pre-wrap">
                                                @if (is_object($value) || is_array($value))
                                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @elseif ($auditLog->action === 'deleted')
                                        @foreach ((array) $auditLog->old_values as $field => $value)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-pre-wrap">
                                                @if (is_object($value) || is_array($value))
                                                    <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 