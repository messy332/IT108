@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Progress Log Details</h1>
            <p class="text-gray-600 mt-1">{{ $progressLog->log_date->format('F j, Y') }} - {{ $progressLog->crop->crop_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('farmer.progress-logs.edit', $progressLog) }}" 
            class=" text-clack px-4 py-2 font-medium hover:underline">
                Edit Log
            </a>
            <a href="{{ route('farmer.crops.show', $progressLog->crop) }}" 
                class=" text-clack px-4 py-2 font-medium hover:underline">
                View Crop
            </a>
            <button type="button" onclick="openDeleteModal()" class="text-red-600 px-4 py-2 font-medium hover:underline">
                Delete
            </button>
            <form id="delete-form" action="{{ route('farmer.progress-logs.destroy', $progressLog) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Log Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Activity Details</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Date</label>
                    <p class="text-gray-900">{{ $progressLog->log_date->format('F j, Y') }} ({{ $progressLog->log_date->format('l') }})</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Activity Type</label>
                    <span class="block mt-1 px-2 py-1 text-sm font-semibold rounded-full w-fit
                        @switch($progressLog->activity_type)
                            @case('planting') bg-green-100 text-green-800 @break
                            @case('watering') bg-blue-100 text-blue-800 @break
                            @case('fertilizing') bg-yellow-100 text-yellow-800 @break
                            @case('weeding') bg-orange-100 text-orange-800 @break
                            @case('pest_control') bg-red-100 text-red-800 @break
                            @case('harvesting') bg-purple-100 text-purple-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ ucfirst(str_replace('_', ' ', $progressLog->activity_type)) }}
                    </span>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Description</label>
                    <p class="text-gray-900">{{ $progressLog->description }}</p>
                </div>
                
                @if($progressLog->cost)
                <div>
                    <label class="text-sm font-medium text-gray-500">Cost</label>
                    <p class="text-gray-900">₱{{ number_format($progressLog->cost, 2) }}</p>
                </div>
                @endif
                
                @if($progressLog->weather_condition)
                <div>
                    <label class="text-sm font-medium text-gray-500">Weather Condition</label>
                    <span class="block mt-1 px-2 py-1 text-sm rounded-full w-fit
                        @switch($progressLog->weather_condition)
                            @case('sunny') bg-yellow-100 text-yellow-800 @break
                            @case('cloudy') bg-gray-100 text-gray-800 @break
                            @case('rainy') bg-blue-100 text-blue-800 @break
                            @case('stormy') bg-red-100 text-red-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ ucfirst($progressLog->weather_condition) }}
                    </span>
                </div>
                @endif
                
                @if($progressLog->growth_stage)
                <div>
                    <label class="text-sm font-medium text-gray-500">Growth Stage</label>
                    <div class="flex items-center mt-1">
                        <span class="text-gray-900 mr-3">{{ $progressLog->growth_stage }}/10</span>
                        <div class="w-32 bg-gray-200 rounded-full h-3">
                            <div class="bg-green-600 h-3 rounded-full" style="width: {{ ($progressLog->growth_stage / 10) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($progressLog->observations)
                <div>
                    <label class="text-sm font-medium text-gray-500">Observations</label>
                    <p class="text-gray-900">{{ $progressLog->observations }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Crop Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Crop Information</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Crop Name</label>
                    <p class="text-gray-900">{{ $progressLog->crop->crop_name }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Variety</label>
                    <p class="text-gray-900">{{ $progressLog->crop->variety ?: 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Area Planted</label>
                    <p class="text-gray-900">{{ $progressLog->crop->area_planted }} hectares</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Planting Date</label>
                    <p class="text-gray-900">{{ $progressLog->crop->planting_date->format('F j, Y') }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @switch($progressLog->crop->status)
                            @case('planted') bg-blue-100 text-blue-800 @break
                            @case('growing') bg-green-100 text-green-800 @break
                            @case('harvested') bg-gray-100 text-gray-800 @break
                            @case('failed') bg-red-100 text-red-800 @break
                        @endswitch">
                        {{ ucfirst($progressLog->crop->status) }}
                    </span>
                </div>
                
                @if($progressLog->crop->expected_harvest_date)
                <div>
                    <label class="text-sm font-medium text-gray-500">Expected Harvest</label>
                    <p class="text-gray-900">{{ $progressLog->crop->expected_harvest_date->format('F j, Y') }}</p>
                </div>
                @endif
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('farmer.crops.show', $progressLog->crop) }}" 
                   class="text-green-600 hover:text-green-900 font-medium">
                    View Full Crop Details →
                </a>
            </div>
        </div>
    </div>

    <!-- Timeline Context -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity Timeline</h2>
        
        <div class="space-y-4">
            @php
                $recentLogs = $progressLog->crop->progressLogs()
                    ->orderBy('log_date', 'desc')
                    ->limit(5)
                    ->get();
            @endphp
            
            @foreach($recentLogs as $log)
                <div class="flex items-start space-x-3 {{ $log->id === $progressLog->id ? 'bg-purple-50 p-3 rounded-lg' : '' }}">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $log->id === $progressLog->id ? 'bg-purple-600' : 'bg-gray-400' }} rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ ucfirst(str_replace('_', ' ', $log->activity_type)) }}
                            @if($log->id === $progressLog->id)
                                <span class="text-purple-600 font-semibold">(Current)</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $log->description }}</p>
                        <p class="text-xs text-gray-400">{{ $log->log_date->format('M j, Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
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
            <p class="text-sm text-center font-medium text-gray-700 mb-4">{{ ucfirst(str_replace('_', ' ', $progressLog->activity_type)) }} - {{ $progressLog->log_date->format('M j, Y') }}</p>
            <p class="text-xs text-center text-red-500 mb-6">This action cannot be undone.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button type="button" onclick="document.getElementById('delete-form').submit()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endsection
