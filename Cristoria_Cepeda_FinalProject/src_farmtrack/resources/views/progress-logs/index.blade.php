@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Progress Logs</h1>
    <div class="mb-6 space-y-3">
        <form id="progress-logs-filter-form" method="GET" action="{{ route('progress-logs.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search logs..." class="w-full pl-9 pr-3 py-2 border border-gray-300 hover:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    </span>
                </div>
                
                <select name="growth_stage" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:w-48">
                    <option value="">All Growth Stages</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('growth_stage') == $i ? 'selected' : '' }}>Stage {{ $i }}/10</option>
                    @endfor
                </select>

                <select name="variety" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:w-40">
                    <option value="">All Varieties</option>
                    @foreach($varieties as $variety)
                        <option value="{{ $variety }}" {{ request('variety') == $variety ? 'selected' : '' }}>{{ $variety }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="flex flex-wrap gap-2">
            <button type="submit" form="progress-logs-filter-form" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium whitespace-nowrap">
                Apply Filters
            </button>
            @if(request()->hasAny(['q', 'growth_stage', 'variety']))
                <a href="{{ route('progress-logs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium text-center whitespace-nowrap">
                    Clear
                </a>
            @endif
            <a href="{{ route('progress-logs.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium whitespace-nowrap">
                Add New Log
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Varieties</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Growth Stage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weather</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $log->log_date->format('M j, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $log->log_date->format('l') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->crop->farmer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $log->crop->farmer->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $log->crop->crop_name }}</div>
                                <div class="text-sm text-gray-500">{{ $log->crop->variety }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @switch($log->activity_type)
                                        @case('planting') bg-green-100 text-green-800 @break
                                        @case('watering') bg-blue-100 text-blue-800 @break
                                        @case('fertilizing') bg-yellow-100 text-yellow-800 @break
                                        @case('weeding') bg-orange-100 text-orange-800 @break
                                        @case('pest_control') bg-red-100 text-red-800 @break
                                        @case('harvesting') bg-purple-100 text-purple-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->growth_stage)
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-900 mr-2">{{ $log->growth_stage }}/10</div>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($log->growth_stage / 10) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->cost ? '₱' . number_format($log->cost, 2) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->weather_condition)
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @switch($log->weather_condition)
                                            @case('sunny') bg-yellow-100 text-yellow-800 @break
                                            @case('cloudy') bg-gray-100 text-gray-800 @break
                                            @case('rainy') bg-blue-100 text-blue-800 @break
                                            @case('stormy') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ ucfirst($log->weather_condition) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('progress-logs.show', $log) }}" class="text-blue-600 hover:text-blue-900 mr-3 inline-block" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                <a href="{{ route('progress-logs.show', $log) }}" class="text-green-600 hover:text-green-900 mr-3 inline-block" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900 inline-block" title="Delete"
                                        onclick="openDeleteModal('{{ $log->id }}', '{{ ucfirst(str_replace('_', ' ', $log->activity_type)) }} ({{ $log->log_date->format('M j, Y') }})')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $log->id }}" action="{{ route('progress-logs.destroy', $log) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No progress logs found. <a href="{{ route('progress-logs.create') }}" class="text-purple-600 hover:text-purple-900">Add the first log</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $logs->appends(['q' => request('q'), 'growth_stage' => request('growth_stage'), 'variety' => request('variety')])->links() }}
            </div>
        @endif
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDeleteModal()"></div>
        <div class="relative z-10 inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Delete Progress Log</h3>
            <p class="text-sm text-center text-gray-500 mb-1">Are you sure you want to delete this progress log?</p>
            <p id="deleteItemName" class="text-sm text-center font-medium text-gray-700 mb-4"></p>
            <p class="text-xs text-center text-red-500 mb-6">This action cannot be undone.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button type="button" onclick="confirmDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteFormId = null;

    function openDeleteModal(id, name) {
        deleteFormId = id;
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteFormId = null;
    }

    function confirmDelete() {
        if (deleteFormId) {
            document.getElementById('delete-form-' + deleteFormId).submit();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endsection