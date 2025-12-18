@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <a href="{{ route('farmers.show', $crop->farmer_id) }}" class="text-gray-900 hover:underline font-normal text-md">
            ← Back to Farmer
        </a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $crop->crop_name }}</h1>
            <p class="text-gray-600 mt-1">{{ $crop->variety }} - {{ $crop->farmer->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('crops.create') }}" 
            class=" text-clack px-4 py-2 font-medium hover:underline">
                Add Crop
            </a>
            <a href="{{ route('crops.edit', $crop) }}" 
            class=" text-clack px-4 py-2 font-medium hover:underline">
                Edit Crop
            </a>
            <a href="{{ route('progress-logs.create') }}?crop_id={{ $crop->id }}" 
                class=" text-clack px-4 py-2 font-medium hover:underline">
                Add Progress Log
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Crop Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Crop Details</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Crop Name</label>
                        <p class="text-gray-900">{{ $crop->crop_name }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Variety</label>
                        <p class="text-gray-900">{{ $crop->variety ?: 'Not specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Area Planted</label>
                        <p class="text-gray-900">{{ $crop->area_planted }} hectares</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Season</label>
                        <p class="text-gray-900 capitalize">{{ $crop->season }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @switch($crop->status)
                                @case('scheduled') bg-yellow-100 text-yellow-800 @break
                                @case('planted') bg-blue-100 text-blue-800 @break
                                @case('growing') bg-green-100 text-green-800 @break
                                @case('harvested') bg-gray-100 text-gray-800 @break
                                @case('failed') bg-red-100 text-red-800 @break
                            @endswitch">
                            {{ ucfirst($crop->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Planting Date</label>
                        <p class="text-gray-900">{{ $crop->planting_date->format('F j, Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Expected Harvest</label>
                        <p class="text-gray-900">{{ $crop->expected_harvest_date ? $crop->expected_harvest_date->format('F j, Y') : 'Not set' }}</p>
                    </div>
                    
                    @if($crop->actual_harvest_date)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Actual Harvest</label>
                        <p class="text-gray-900">{{ $crop->actual_harvest_date->format('F j, Y') }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Expected Yield</label>
                        <p class="text-gray-900">{{ $crop->expected_yield ? number_format($crop->expected_yield, 2) . ' kg' : 'Not set' }}</p>
                    </div>
                    
                    @if($crop->actual_yield)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Actual Yield</label>
                        <p class="text-gray-900">{{ number_format($crop->actual_yield, 2) }} kg</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Harvest Proof Image -->
            @if($crop->harvest_image)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Harvest/Failure Proof</h2>
                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $crop->harvest_image) }}" alt="Harvest proof" class="max-h-96 max-w-full object-contain rounded border border-gray-300">
                </div>
            </div>
            @endif

            <!-- Progress Stats -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Progress Overview</h2>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Days Since Planting</span>
                            <span>{{ $crop->days_planted }} days</span>
                        </div>
                    </div>
                    
                    @if($crop->expected_harvest_date)
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Days to Harvest</span>
                            <span>{{ $crop->days_to_harvest }} days</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $crop->progress_percentage }}%"></div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-lg font-bold text-blue-600">{{ $crop->progress_percentage }}%</span>
                            <span class="text-sm text-gray-600 ml-2">Complete</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Logs</span>
                        <span class="font-semibold">{{ $crop->progressLogs->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Logs -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Progress Logs</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Growth</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Weather</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($crop->progressLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->log_date->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->growth_stage ? $log->growth_stage . '/10' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->cost ? '₱' . number_format($log->cost, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                        {{ $log->weather_condition ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('progress-logs.show', $log) }}" class="text-blue-600 hover:text-blue-900 inline-block" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No progress logs yet. 
                                        <a href="{{ route('progress-logs.create') }}?crop_id={{ $crop->id }}" class="text-purple-600 hover:text-purple-900">Add the first log</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection