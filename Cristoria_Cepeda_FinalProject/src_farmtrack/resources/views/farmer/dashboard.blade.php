@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Dashboard Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Farm Dashboard</h1>
        <p class="text-gray-600 mt-1">Welcome back, {{ $farmer->name }}</p>
    </div>
    <!-- Search Bar -->
    <form method="GET" action="{{ route('farmer.dashboard') }}" class="mb-6">
        <div class="flex gap-2">
            <div class="relative flex-1">
                <input type="text" 
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Search crops, activities..." 
                       class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
                Search
            </button>
            @if(request('q'))
                <a href="{{ route('farmer.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Clear
                </a>
            @endif
        </div>
    </form>

    @if($searchResults)
    <!-- Search Results -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">Search Results for "{{ $searchResults['query'] }}"</h2>
            <span class="text-sm text-gray-500">
                {{ $searchResults['crops']->count() }} crops, {{ $searchResults['activities']->count() }} activities found
            </span>
        </div>

        @if($searchResults['crops']->isEmpty() && $searchResults['activities']->isEmpty())
            <p class="text-gray-500 text-center py-4">No results found for "{{ $searchResults['query'] }}"</p>
        @else
            @if($searchResults['crops']->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Crops</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($searchResults['crops']->take(6) as $crop)
                        <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer" onclick="window.location='{{ route('farmer.crops.show', $crop) }}'">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $crop->crop_name }}</h4>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $crop->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($crop->status === 'planted' ? 'bg-blue-100 text-blue-800' : 
                                       ($crop->status === 'growing' ? 'bg-green-100 text-green-800' : 
                                       ($crop->status === 'harvested' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                                    {{ ucfirst($crop->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $crop->variety }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $crop->area_planted }} ha</p>
                        </div>
                    @endforeach
                </div>
                @if($searchResults['crops']->count() > 6)
                    <a href="{{ route('farmer.crops.index', ['q' => $searchResults['query']]) }}" class="text-green-600 hover:text-green-800 text-sm font-medium mt-3 inline-block">
                        View all {{ $searchResults['crops']->count() }} crops →
                    </a>
                @endif
            </div>
            @endif

            @if($searchResults['activities']->isNotEmpty())
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Activities</h3>
                <div class="space-y-3">
                    @foreach($searchResults['activities']->take(5) as $activity)
                        <div class="flex items-start space-x-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('farmer.progress-logs.show', $activity) }}'">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->crop->crop_name }}</p>
                                    <span class="text-xs text-gray-500">{{ $activity->log_date->format('M j, Y') }}</span>
                                </div>
                                <p class="text-sm text-gray-600">
                                    {{ \App\Models\ProgressLog::getActivityTypes()[$activity->activity_type] ?? $activity->activity_type }}
                                </p>
                                @if($activity->description)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $activity->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($searchResults['activities']->count() > 5)
                    <a href="{{ route('farmer.progress-logs.index', ['q' => $searchResults['query']]) }}" class="text-green-600 hover:text-green-800 text-sm font-medium mt-3 inline-block">
                        View all {{ $searchResults['activities']->count() }} activities →
                    </a>
                @endif
            </div>
            @endif
        @endif
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Crops</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_crops'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Crops</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_crops'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Area</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_area'], 1) }} ha</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Growth</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['avg_growth_stage'], 1) }}/10</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Farm Overview Section (Map + Recent Activity) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Map Column -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow flex flex-col min-h-[600px]">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Farm Location</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($farmer->address)
                            {{ $farmer->address }}
                        @else
                            Click "Set Farm Location" to start
                        @endif
                    </p>
                </div>
                <button onclick="openLocationModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    @if($farmer->latitude && $farmer->longitude)
                        Update Location
                    @else
                        Set Location
                    @endif
                </button>
            </div>
            <div class="p-4 flex-grow relative">
                <div id="farmMap" class="w-full h-full rounded-lg border border-gray-200 z-0"></div>
                <!-- Map Overlay Legend -->
                <div class="absolute bottom-6 left-6 bg-white bg-opacity-90 p-3 rounded shadow-md text-xs z-[400]">
                    <div class="font-semibold mb-2">Legend</div>
                    <div class="flex items-center mb-1"><span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span> Scheduled</div>
                    <div class="flex items-center mb-1"><span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span> Planted</div>
                    <div class="flex items-center mb-1"><span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span> Growing</div>
                    <div class="flex items-center mb-1"><span class="w-3 h-3 rounded-full bg-gray-500 mr-2"></span> Harvested</div>
                    <div class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span> Failed</div>
                </div>
            </div>
        </div>

        <!-- Info/Activity Column -->
        <div class="bg-white rounded-lg shadow flex flex-col h-full max-h-[600px]">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900" id="infoPanelTitle">Recent Activity</h2>
                <a href="{{ route('farmer.progress-logs.index') }}" id="viewAllLink" class="text-green-600 hover:text-green-800 text-sm font-medium">
                    View All →
                </a>
            </div>
            
            <div class="p-6 overflow-y-auto flex-grow custom-scrollbar">
                <!-- Recent Activity Panel (Default) -->
                <div id="defaultInfoPanel" class="space-y-4">
                    @forelse($recentActivities->take(5) as $activity)
                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->crop->crop_name }}</p>
                                    <span class="text-xs text-gray-500">{{ $activity->log_date->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-500">{{ $activity->crop->variety }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ \App\Models\ProgressLog::getActivityTypes()[$activity->activity_type] ?? $activity->activity_type }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $activity->description }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No recent activities recorded</p>
                    @endforelse
                </div>

                <!-- Dynamic Crop Info Panel (Hidden by default) -->
                <div id="cropInfoPanel" class="hidden space-y-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" id="cropName">Crop Name</h3>
                            <p class="text-sm text-green-600 font-medium" id="cropVariety">Variety</p>
                        </div>
                        <span id="cropStatusBadge" class="px-2 py-1 rounded-full text-xs font-semibold">Status</span>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Area</p>
                                <p class="font-semibold text-gray-900" id="cropArea">0 ha</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Season</p>
                                <p class="font-semibold text-gray-900" id="cropSeason">Season</p>
                            </div>
                        </div>

                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <p class="text-xs text-gray-500">Planting Date</p>
                            <p class="font-medium text-gray-900" id="cropPlantingDate">Date</p>
                        </div>
                        
                        <div class="border-l-4 border-blue-500 pl-4 py-1">
                            <p class="text-xs text-gray-500">Expected Harvest</p>
                            <p class="font-medium text-gray-900" id="cropHarvestDate">Date</p>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Current Growth Stage</span>
                                <span class="text-sm font-bold text-green-600" id="cropGrowthStage">0/10</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div id="cropGrowthBar" class="bg-green-600 h-2.5 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex space-x-3">
                        <a id="cropDetailsLink" href="#" class="flex-1 bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 transition">
                            View Full Details
                        </a>
                        <button onclick="resetInfoPanel()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600">
                            Back
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farm Status Graph -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Farm Status (Last 6 Months)</h2>
        <div style="height: 300px;">
            <canvas id="farmStatusChart"></canvas>
        </div>
    </div>

    <!-- Activity Types by Growth Rate -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Activity Types by Average Growth Rate (1-10)</h2>
        <div style="height: 350px;">
            <canvas id="activityGrowthChart"></canvas>
        </div>
    </div>

    <!-- Farm Area Utilization -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Farm Area Utilization</h2>
        <div class="flex justify-center" style="height: 350px;">
            <canvas id="areaUtilizationChart"></canvas>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4 text-center">
            <div class="p-3 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Planted Area</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($areaUtilization['planted'], 2) }} ha</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">Available Area</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($areaUtilization['available'], 2) }} ha</p>
            </div>
        </div>
    </div>

    <!-- My Crops Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">My Crops</h2>
            <a href="{{ route('farmer.crops.index') }}" 
               class="text-green-600 hover:text-green-800 text-sm font-medium">
                View All →
            </a>
        </div>

        @if($crops->isEmpty())
            <p class="text-gray-500 text-center py-8">No crops registered yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($crops->take(6) as $crop)
                    <div class="border rounded-lg p-4 hover:shadow-md transition cursor-pointer" 
                         onclick="showCropOnMap({{ json_encode($crop) }})">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $crop->crop_name }}</h3>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $crop->status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($crop->status === 'planted' ? 'bg-blue-100 text-blue-800' : 
                                   ($crop->status === 'growing' ? 'bg-green-100 text-green-800' : 
                                   ($crop->status === 'harvested' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                                {{ ucfirst($crop->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $crop->variety }}</p>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Area:</span>
                                <span class="font-medium">{{ $crop->area_planted }} ha</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Season:</span>
                                <span class="font-medium">{{ ucfirst($crop->season) }}</span>
                            </div>
                            @if($crop->latestProgressLog)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Growth:</span>
                                    <span class="font-medium">{{ $crop->latestProgressLog->growth_stage }}/10</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Progress:</span>
                                <span class="font-medium">{{ $crop->progress_percentage }}%</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

<!-- Location Update Modal -->
<div id="locationModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Set Farm Location</h3>
            <button onclick="closeLocationModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="locationForm" action="{{ route('farmer.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Farm Address</label>
                <div class="flex gap-2">
                    <input type="text" name="address" id="addressInput" value="{{ $farmer->address }}" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                           placeholder="Enter your farm address (e.g., Barangay, Municipality, Province)"
                           onchange="searchAddress(this.value, true)">
                </div>
            </div>
            
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                <p class="text-sm text-blue-800">
                    <strong>How to set your farm location:</strong>
                </p>
                <ul class="text-sm text-blue-700 mt-2 space-y-1 ml-4 list-disc">
                    <li>Type your address to automatically search on map</li>
                    <li>Click "Use My Current Location" to automatically detect your location</li>
                    <li>Or click anywhere on the map below to mark your farm location</li>
                    <li>Use the polygon tool (⬟) to draw your farm boundaries</li>
                </ul>
            </div>
            
            <div class="mb-4">
                <button type="button" onclick="getCurrentLocation()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    📍 Use My Current Location
                </button>
            </div>
            
            <div id="updateMap" style="height: 400px;" class="mb-4 rounded-lg border border-gray-300"></div>
            
            <input type="hidden" name="latitude" id="latitudeInput">
            <input type="hidden" name="longitude" id="longitudeInput">
            <input type="hidden" name="farm_boundaries" id="farmBoundariesInput">
            
            <!-- Keep other fields unchanged -->
            <input type="hidden" name="name" value="{{ $farmer->name }}">
            <input type="hidden" name="phone" value="{{ $farmer->phone }}">
            <input type="hidden" name="birthdate" value="{{ $farmer->birthdate ? $farmer->birthdate->format('Y-m-d') : '' }}">
            <input type="hidden" name="gender" value="{{ $farmer->gender }}">
            <input type="hidden" name="farm_size" value="{{ $farmer->farm_size }}">
            <input type="hidden" name="farm_type" value="{{ $farmer->farm_type }}">
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeLocationModal()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Save Location
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<style>
    .leaflet-popup-content-wrapper { border-radius: 0.5rem; }
    .leaflet-popup-content { margin: 0; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
let farmMap, updateMap, farmPolygon, updatePolygon, drawnItems, currentMarker;
const farmerData = @json($farmer);
const cropsData = @json($mapCrops);

// Default coordinates for Philippines (Manila)
const PHILIPPINES_CENTER = { lat: 14.5995, lng: 120.9842 };

document.addEventListener('DOMContentLoaded', function() {
    initializeFarmMap();
    initializeCharts();
});

function initializeFarmMap() {
    const defaultLat = farmerData.latitude || PHILIPPINES_CENTER.lat;
    const defaultLng = farmerData.longitude || PHILIPPINES_CENTER.lng;
    const defaultZoom = (farmerData.latitude && farmerData.longitude) ? 15 : 6;
    
    farmMap = L.map('farmMap').setView([defaultLat, defaultLng], defaultZoom);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(farmMap);
    
    // Auto-search address if no coordinates
    if (!farmerData.latitude && farmerData.address) {
        searchAddress(farmerData.address, false);
    }
    
    // Add farm boundaries if they exist
    if (farmerData.farm_boundaries && farmerData.farm_boundaries.length > 0) {
        const bounds = farmerData.farm_boundaries.map(coord => [coord.lat, coord.lng]);
        
        farmPolygon = L.polygon(bounds, {
            color: '#16a34a',
            fillColor: '#22c55e',
            fillOpacity: 0.2,
            weight: 3
        }).addTo(farmMap);
        
        // Click on polygon resets to farm info
        farmPolygon.on('click', function() {
            resetInfoPanel();
            farmMap.fitBounds(farmPolygon.getBounds());
        });
        
        farmMap.fitBounds(farmPolygon.getBounds());
        
        addCropMarkers();
    } else if (farmerData.latitude && farmerData.longitude) {
        const marker = L.marker([defaultLat, defaultLng], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(farmMap);
        
        marker.on('click', function() {
            resetInfoPanel();
        });
        
        addCropMarkers();
    }
}

function addCropMarkers() {
    if (!farmerData.latitude || !farmerData.longitude || cropsData.length === 0) return;
    
    cropsData.forEach((crop, index) => {
        // Distribute markers in a circle if no specific coordinates (simplified for now)
        // In a real app, crops might have their own polygons/coordinates
        const offset = 0.0005 + (Math.random() * 0.0005);
        const angle = (index * 360 / cropsData.length) * Math.PI / 180;
        const lat = parseFloat(farmerData.latitude) + (offset * Math.cos(angle));
        const lng = parseFloat(farmerData.longitude) + (offset * Math.sin(angle));
        
        const statusColors = {
            'scheduled': 'gold',
            'planted': 'blue',
            'growing': 'green',
            'harvested': 'grey',
            'failed': 'red'
        };
        
        const color = statusColors[crop.status] || 'orange';
        
        const cropMarker = L.marker([lat, lng], {
            icon: L.icon({
                iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${color}.png`,
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [20, 33],
                iconAnchor: [10, 33],
                popupAnchor: [1, -28],
                shadowSize: [33, 33]
            })
        }).addTo(farmMap);
        
        // On click, update the Info Panel
        cropMarker.on('click', function() {
            updateInfoPanel(crop);
            
            // Also show a small popup for quick ID
            L.popup()
                .setLatLng([lat, lng])
                .setContent(`<div class="font-bold text-sm">${crop.crop_name}</div><div class="text-xs text-gray-600">${crop.variety}</div>`)
                .openOn(farmMap);
        });
    });
}

function updateInfoPanel(crop) {
    document.getElementById('defaultInfoPanel').classList.add('hidden');
    document.getElementById('cropInfoPanel').classList.remove('hidden');
    document.getElementById('infoPanelTitle').textContent = 'Crop Details';
    if(document.getElementById('viewAllLink')) document.getElementById('viewAllLink').classList.add('hidden');
    
    document.getElementById('cropName').textContent = crop.crop_name;
    document.getElementById('cropVariety').textContent = crop.variety || 'N/A';
    
    // Update Badge
    const badge = document.getElementById('cropStatusBadge');
    badge.textContent = crop.status.charAt(0).toUpperCase() + crop.status.slice(1);
    badge.className = 'px-2 py-1 rounded-full text-xs font-semibold ' + 
        (crop.status === 'scheduled' ? 'bg-yellow-100 text-yellow-800' :
        (crop.status === 'planted' ? 'bg-blue-100 text-blue-800' : 
        (crop.status === 'growing' ? 'bg-green-100 text-green-800' : 
        (crop.status === 'harvested' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))));
        
    document.getElementById('cropArea').textContent = crop.area_planted + ' ha';
    document.getElementById('cropSeason').textContent = crop.season.charAt(0).toUpperCase() + crop.season.slice(1);
    
    const plantingDate = new Date(crop.planting_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    document.getElementById('cropPlantingDate').textContent = plantingDate;
    
    const harvestDate = crop.expected_harvest_date ? new Date(crop.expected_harvest_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : 'Not set';
    document.getElementById('cropHarvestDate').textContent = harvestDate;
    
    const growthStage = crop.latest_progress_log ? crop.latest_progress_log.growth_stage : 0;
    document.getElementById('cropGrowthStage').textContent = growthStage + '/10';
    document.getElementById('cropGrowthBar').style.width = (growthStage * 10) + '%';
    
    document.getElementById('cropDetailsLink').href = `/farmer/crops/${crop.id}`;
}

function resetInfoPanel() {
    document.getElementById('cropInfoPanel').classList.add('hidden');
    document.getElementById('defaultInfoPanel').classList.remove('hidden');
    document.getElementById('infoPanelTitle').textContent = 'Recent Activity';
    if(document.getElementById('viewAllLink')) document.getElementById('viewAllLink').classList.remove('hidden');
}

function showCropOnMap(crop) {
    if (!farmerData.latitude || !farmerData.longitude) {
        alert('Farm location not set. Please set your farm location first.');
        return;
    }
    
    updateInfoPanel(crop);
    
    // Scroll to map
    document.getElementById('farmMap').scrollIntoView({ behavior: 'smooth' });
    
    // Trigger marker click if we could find it, but for now just center map
    // In a real app we'd track marker references
    farmMap.setView([farmerData.latitude, farmerData.longitude], 17);
}

function openLocationModal() {
    document.getElementById('locationModal').classList.remove('hidden');
    
    setTimeout(() => {
        if (!updateMap) {
            const defaultLat = farmerData.latitude || PHILIPPINES_CENTER.lat;
            const defaultLng = farmerData.longitude || PHILIPPINES_CENTER.lng;
            const defaultZoom = (farmerData.latitude && farmerData.longitude) ? 15 : 6;
            
            updateMap = L.map('updateMap').setView([defaultLat, defaultLng], defaultZoom);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(updateMap);
            
            // Initialize drawing controls
            drawnItems = new L.FeatureGroup();
            updateMap.addLayer(drawnItems);
            
            const drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems,
                    edit: true,
                    remove: true
                },
                draw: {
                    polygon: {
                        allowIntersection: false,
                        showArea: true,
                        shapeOptions: {
                            color: '#16a34a',
                            fillColor: '#22c55e',
                            fillOpacity: 0.3
                        }
                    },
                    polyline: false,
                    circle: false,
                    rectangle: false,
                    marker: false,
                    circlemarker: false
                }
            });
            updateMap.addControl(drawControl);
            
            // Load existing boundaries if available
            if (farmerData.farm_boundaries && farmerData.farm_boundaries.length > 0) {
                const bounds = farmerData.farm_boundaries.map(coord => [coord.lat, coord.lng]);
                updatePolygon = L.polygon(bounds, {
                    color: '#16a34a',
                    fillColor: '#22c55e',
                    fillOpacity: 0.3
                }).addTo(drawnItems);
                updateMap.fitBounds(updatePolygon.getBounds());
                
                // Set hidden inputs
                const center = updatePolygon.getBounds().getCenter();
                document.getElementById('latitudeInput').value = center.lat.toFixed(8);
                document.getElementById('longitudeInput').value = center.lng.toFixed(8);
                document.getElementById('farmBoundariesInput').value = JSON.stringify(farmerData.farm_boundaries);
            } else if (farmerData.latitude && farmerData.longitude) {
                // Add existing marker
                currentMarker = L.marker([farmerData.latitude, farmerData.longitude], {
                    draggable: true,
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(updateMap);
                
                currentMarker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    document.getElementById('latitudeInput').value = pos.lat.toFixed(8);
                    document.getElementById('longitudeInput').value = pos.lng.toFixed(8);
                });
                
                document.getElementById('latitudeInput').value = farmerData.latitude;
                document.getElementById('longitudeInput').value = farmerData.longitude;
            }
            
            // Handle polygon creation
            updateMap.on(L.Draw.Event.CREATED, function(e) {
                const layer = e.layer;
                drawnItems.clearLayers();
                drawnItems.addLayer(layer);
                updatePolygon = layer;
                
                // Remove marker if exists
                if (currentMarker) {
                    updateMap.removeLayer(currentMarker);
                    currentMarker = null;
                }
                
                const coords = layer.getLatLngs()[0];
                const boundaries = coords.map(coord => ({
                    lat: coord.lat,
                    lng: coord.lng
                }));
                
                document.getElementById('farmBoundariesInput').value = JSON.stringify(boundaries);
                
                const center = layer.getBounds().getCenter();
                document.getElementById('latitudeInput').value = center.lat.toFixed(8);
                document.getElementById('longitudeInput').value = center.lng.toFixed(8);
            });
            
            // Handle polygon edit
            updateMap.on(L.Draw.Event.EDITED, function(e) {
                const layers = e.layers;
                layers.eachLayer(function(layer) {
                    const coords = layer.getLatLngs()[0];
                    const boundaries = coords.map(coord => ({
                        lat: coord.lat,
                        lng: coord.lng
                    }));
                    
                    document.getElementById('farmBoundariesInput').value = JSON.stringify(boundaries);
                    
                    const center = layer.getBounds().getCenter();
                    document.getElementById('latitudeInput').value = center.lat.toFixed(8);
                    document.getElementById('longitudeInput').value = center.lng.toFixed(8);
                });
            });
            
            // Handle polygon delete
            updateMap.on(L.Draw.Event.DELETED, function() {
                document.getElementById('farmBoundariesInput').value = '';
            });
            
            // Click on map to set center point
            updateMap.on('click', function(e) {
                document.getElementById('latitudeInput').value = e.latlng.lat.toFixed(8);
                document.getElementById('longitudeInput').value = e.latlng.lng.toFixed(8);
                
                // Add or move marker
                if (currentMarker) {
                    currentMarker.setLatLng(e.latlng);
                } else {
                    currentMarker = L.marker(e.latlng, {
                        draggable: true,
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(updateMap);
                    
                    currentMarker.on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        document.getElementById('latitudeInput').value = pos.lat.toFixed(8);
                        document.getElementById('longitudeInput').value = pos.lng.toFixed(8);
                    });
                }
            });
        } else {
            updateMap.invalidateSize();
        }
    }, 100);
}

function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById('latitudeInput').value = lat.toFixed(8);
            document.getElementById('longitudeInput').value = lng.toFixed(8);
            
            if (updateMap) {
                updateMap.setView([lat, lng], 15);
                
                // Add or move marker
                if (currentMarker) {
                    currentMarker.setLatLng([lat, lng]);
                } else {
                    currentMarker = L.marker([lat, lng], {
                        draggable: true,
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(updateMap);
                    
                    currentMarker.on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        document.getElementById('latitudeInput').value = pos.lat.toFixed(8);
                        document.getElementById('longitudeInput').value = pos.lng.toFixed(8);
                    });
                }
            }
        }, function(error) {
            alert('Unable to get your location: ' + error.message);
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

function initializeCharts() {
    // Farm Status Chart (Line Chart)
    const farmStatusCtx = document.getElementById('farmStatusChart');
    if (farmStatusCtx) {
        const farmStatusData = @json($farmStatusData);
        
        new Chart(farmStatusCtx, {
            type: 'line',
            data: {
                labels: Object.keys(farmStatusData),
                datasets: [{
                    label: 'Average Growth Stage',
                    data: Object.values(farmStatusData),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        title: {
                            display: true,
                            text: 'Growth Stage (1-10)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }

    // Activity Growth Chart (Vertical Bar Chart)
    const activityGrowthCtx = document.getElementById('activityGrowthChart');
    if (activityGrowthCtx) {
        const activityGrowthData = @json($activityByGrowth);
        
        new Chart(activityGrowthCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(activityGrowthData),
                datasets: [{
                    label: 'Average Growth Rate',
                    data: Object.values(activityGrowthData),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                        'rgba(20, 184, 166, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(132, 204, 22, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)',
                        'rgb(20, 184, 166)',
                        'rgb(251, 146, 60)',
                        'rgb(99, 102, 241)',
                        'rgb(132, 204, 22)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        title: {
                            display: true,
                            text: 'Growth Rate (1-10)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Activity Type'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Farm Area Utilization Chart (Doughnut Chart)
    const areaUtilizationCtx = document.getElementById('areaUtilizationChart');
    if (areaUtilizationCtx) {
        const areaUtilizationData = @json($areaUtilization);
        
        new Chart(areaUtilizationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Planted Area', 'Available Area'],
                datasets: [{
                    data: [areaUtilizationData.planted, areaUtilizationData.available],
                    backgroundColor: ['rgb(34, 197, 94)', 'rgb(229, 231, 235)'],
                    borderColor: ['rgb(22, 163, 74)', 'rgb(209, 213, 219)'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' ha';
                            }
                        }
                    }
                }
            }
        });
    }
}

async function searchAddress(address, isModal = false) {
    if (!address) return;
    
    // Append Philippines context if not present to improve accuracy
    let searchQuery = address;
    if (!searchQuery.toLowerCase().includes('philippines')) {
        searchQuery += ', Philippines';
    }

    try {
        // Use a more specific query parameter structure for better results
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&addressdetails=1&limit=5`);
        const data = await response.json();
        
        if (data && data.length > 0) {
            // Prioritize results that match the specific locality if possible
            const result = data[0];
            const lat = parseFloat(result.lat);
            const lon = parseFloat(result.lon);
            
            console.log('Location found:', result.display_name);
            
            if (isModal && updateMap) {
                updateMap.setView([lat, lon], 16);
                
                // Move marker
                if (currentMarker) {
                    currentMarker.setLatLng([lat, lon]);
                } else {
                    currentMarker = L.marker([lat, lon], {
                        draggable: true,
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(updateMap);
                    
                    currentMarker.on('dragend', function(e) {
                        const pos = e.target.getLatLng();
                        document.getElementById('latitudeInput').value = pos.lat.toFixed(8);
                        document.getElementById('longitudeInput').value = pos.lng.toFixed(8);
                    });
                }
                
                // Update inputs
                document.getElementById('latitudeInput').value = lat.toFixed(8);
                document.getElementById('longitudeInput').value = lon.toFixed(8);
            } else if (!isModal && farmMap) {
                // Only auto-center main map if no coordinates set yet
                if (!farmerData.latitude || !farmerData.longitude) {
                    farmMap.setView([lat, lon], 15);
                    L.marker([lat, lon], {
                        icon: L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        })
                    }).addTo(farmMap)
                    .bindPopup("Approximate location based on address. Please set exact location.")
                    .openPopup();
                }
            }
        } else {
            if (isModal) alert('Address not found on map. Please try a different address or locate manually.');
        }
    } catch (error) {
        console.error('Geocoding error:', error);
        if (isModal) alert('Error searching for address.');
    }
}
</script>
@endpush
@endsection
