@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Farm Monitoring Dashboard</h1>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" id="dashboardSearch" 
                   class="block w-full pl-10 pr-3 py-3 border border-gray-900 hover:border-gray-700 rounded-lg leading-5 bg-white placeholder-gray-900 focus:outline-none focus:placeholder-gray-900 focus:ring-2 focus:ring-gray-900 focus:border-gray-900 sm:text-sm" 
                   placeholder="Search farmers, crops, varieties, or activities...">
        </div>
        <div id="searchResults" class="hidden mt-2 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto"></div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Farmers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_farmers']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Crops</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_crops']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Farm Area</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_farm_area'], 1) }} ha</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Yield</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_yield']) }} kg</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activities</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentLogs->take(5) as $log)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $log->crop->farmer->name }} - {{ $log->crop->crop_name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">{{ Str::limit($log->description, 40) }}</p>
                                <p class="text-xs text-gray-400">{{ $log->log_date->format('M j, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent activities</p>
                    @endforelse
                </div>
                <div class="mt-4">
                    <a href="{{ route('progress-logs.index') }}" class="text-green-600 hover:text-green-900 text-sm font-medium">
                        View all activities →
                    </a>
                </div>
            </div>
        </div>

        <!-- Crops Needing Attention -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Crops Needing Attention</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($cropsNeedingAttention as $crop)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $crop->crop_name }}</p>
                                <p class="text-sm text-gray-500">{{ $crop->farmer->name }}</p>
                                <p class="text-xs text-yellow-600">No activity in 7+ days</p>
                            </div>
                            <a href="{{ route('crops.show', $crop) }}" class="text-yellow-600 hover:text-yellow-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">All crops are up to date!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Upcoming Harvests -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Harvests</h2>
                <p class="text-xs text-gray-500">Next 30 Days</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($upcomingHarvests as $crop)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $crop->farmer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $crop->crop_name }} ({{ $crop->variety }})</p>
                                <p class="text-xs text-green-600 mt-1">{{ $crop->expected_harvest_date->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ abs($crop->days_to_harvest) }} days left</p>
                            </div>
                            <a href="{{ route('crops.show', $crop) }}" class="text-green-600 hover:text-green-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No harvests in next 30 days</p>
                    @endforelse
                </div>
                @if($upcomingHarvests->count() > 0)
                    <div class="mt-4">
                        <a href="{{ route('crops.index') }}" class="text-green-600 hover:text-green-900 text-sm font-medium">
                            View all crops →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Farm Overview Map Section -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Farm Overview Map</h2>
                    <p class="text-sm text-gray-500 mt-1">Interactive visualization of all registered farms</p>
                </div>
                <button onclick="resetMapFilters()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-6">
            <!-- Map Statistics & Filters -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Overview</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                            <p class="text-xs text-green-700 font-medium">Total Farmers</p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($stats['total_farmers']) }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                            <p class="text-xs text-blue-700 font-medium">Total Rice Farms</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalRiceFarms) }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                            <p class="text-xs text-yellow-700 font-medium">Avg Farm Size</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ number_format($avgFarmSize ?? 0, 2) }} ha</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Filters</h3>
                    
                    <!-- Search by Name -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search Farmer</label>
                        <input type="text" id="mapSearchInput" placeholder="Type farmer name..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Filter by Crop Status -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Crop Status</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Status</option>
                            <option value="planted">Planted</option>
                            <option value="growing">Growing</option>
                            <option value="harvested">Harvested</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>

                    <!-- Filter by Farm Size -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Farm Size</label>
                        <select id="sizeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">All Sizes</option>
                            <option value="0-2">Small (0-2 ha)</option>
                            <option value="2-5">Medium (2-5 ha)</option>
                            <option value="5+">Large (5+ ha)</option>
                        </select>
                    </div>
                </div>

                <!-- Legend -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Legend</h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                            <span class="text-gray-700">Planted</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                            <span class="text-gray-700">Growing</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-gray-500 mr-2"></div>
                            <span class="text-gray-700">Harvested</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                            <span class="text-gray-700">Failed</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                            <span class="text-gray-700">No Crops</span>
                        </div>
                    </div>
                </div>

                <!-- Selected Farm Info -->
                <div id="farmInfoPanel" class="hidden space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Farm Details</h3>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Farmer Name</p>
                                <p class="text-sm font-semibold text-gray-900" id="farmFarmerName">-</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-gray-500">Farm Size</p>
                                    <p class="text-sm font-semibold text-gray-900" id="farmSize">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Crops</p>
                                    <p class="text-sm font-semibold text-gray-900" id="farmCrops">-</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-2">Active Crops</p>
                                <div id="farmCropsList" class="space-y-1 max-h-32 overflow-y-auto custom-scrollbar">
                                    <!-- Dynamic content -->
                                </div>
                            </div>
                            <a href="#" id="viewFarmerLink" class="block w-full px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-center text-sm font-medium rounded-lg transition">
                                View Farmer Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="lg:col-span-3">
                <div id="farmsMap" class="w-full h-[600px] rounded-lg border-2 border-gray-200"></div>
            </div>
        </div>
    </div>
    <!-- Charts -->
    <!-- Activity Chart - Full Width -->

    <!-- Bar Chart - Top 10 Most Planted Rice Varieties (Below Report Cards) -->
    <div class="bg-white rounded-lg shadow p-6 mt-8 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Top 10 Most Planted Rice Varieties</h2>
            <span class="text-xs text-gray-500">By number of plantings</span>
        </div>
        <canvas id="topRiceVarietiesBarChart" height="100"></canvas>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Activity (last 6 months)</h2>
        </div>
        <canvas id="areaChart" height="100"></canvas>
    </div>

    <!-- Other Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bar Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Key Metrics</h2>
            </div>
            <canvas id="barChart" height="160"></canvas>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Composition</h2>
            </div>
            <canvas id="pieChart" height="160"></canvas>
        </div>
    </div>

    <!-- New Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Pie Chart - Gender Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Farmer Gender Distribution</h2>
            </div>
            <div class="flex justify-center">
                <canvas id="genderPieChart" height="200"></canvas>
            </div>
        </div>

        <!-- Pie Chart - Activity Type Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Activity Type Distribution</h2>
            </div>
            <div class="flex justify-center">
                <canvas id="activityPieChart" height="200"></canvas>
            </div>
        </div>
    </div>

    
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
// Farm Map Data
const farmersData = @json($farmersWithLocation);
let farmsMap;
let markersCluster;
let allMarkers = [];
let mapInitialized = false;

// Initialize Map - Delayed for performance
document.addEventListener('DOMContentLoaded', function() {
    // Use Intersection Observer to only initialize map when visible
    const mapContainer = document.getElementById('farmsMap');
    if (mapContainer) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !mapInitialized) {
                    setTimeout(() => {
                        initializeFarmsMap();
                        setupMapFilters();
                        mapInitialized = true;
                    }, 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(mapContainer);
    }
});

function initializeFarmsMap() {
    // Default center (Philippines)
    const philippinesCenter = [12.8797, 121.7740];
    
    farmsMap = L.map('farmsMap').setView(philippinesCenter, 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(farmsMap);
    
    // Use marker clustering for better performance with many markers
    markersCluster = L.markerClusterGroup({
        maxClusterRadius: 50,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true
    });
    
    farmsMap.addLayer(markersCluster);
    
    // Add all farm markers
    addFarmMarkers(farmersData);
    
    // Fit bounds if there are markers
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        farmsMap.fitBounds(group.getBounds(), { padding: [50, 50], maxZoom: 13 });
    }
}

function addFarmMarkers(farmers) {
    allMarkers = [];
    markersCluster.clearLayers();
    
    // Process markers in batches to prevent UI blocking
    let index = 0;
    const batchSize = 50; // Increased batch size with clustering
    
    function addBatch() {
        const end = Math.min(index + batchSize, farmers.length);
        const batchMarkers = [];
        
        for (let i = index; i < end; i++) {
            const farmer = farmers[i];
            if (farmer.latitude && farmer.longitude) {
                const marker = createMarker(farmer);
                batchMarkers.push(marker);
                allMarkers.push(marker);
            }
        }
        
        // Add batch to cluster at once for better performance
        markersCluster.addLayers(batchMarkers);
        
        index = end;
        
        if (index < farmers.length) {
            setTimeout(addBatch, 5); // Small delay between batches
        } else {
            // All markers added, fit bounds
            if (allMarkers.length > 0) {
                const group = new L.featureGroup(allMarkers);
                farmsMap.fitBounds(group.getBounds(), { padding: [50, 50], maxZoom: 13 });
            }
        }
    }
    
    addBatch();
}

function createMarker(farmer) {
    const lat = parseFloat(farmer.latitude);
    const lng = parseFloat(farmer.longitude);
    
    // Determine marker color based on crop status
    let markerColor = 'yellow'; // No crops
    let primaryStatus = 'no crops';
    
    if (farmer.crops && farmer.crops.length > 0) {
        // Get most common status
        const statusCounts = {};
        farmer.crops.forEach(crop => {
            statusCounts[crop.status] = (statusCounts[crop.status] || 0) + 1;
        });
        
        primaryStatus = Object.keys(statusCounts).reduce((a, b) => 
            statusCounts[a] > statusCounts[b] ? a : b
        );
        
        const statusColors = {
            'planted': 'blue',
            'growing': 'green',
            'harvested': 'grey',
            'failed': 'red'
        };
        
        markerColor = statusColors[primaryStatus] || 'yellow';
    }
    
    const marker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${markerColor}.png`,
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34]
        })
    });
    
    marker.farmerData = farmer;
    
    // Add popup with clickable link
    marker.bindPopup(`
        <div class="text-sm">
            <p class="font-bold text-gray-900 mb-2">${farmer.name}</p>
            <p class="text-xs text-gray-600">Farm Size: ${farmer.farm_size || 'N/A'} ha</p>
            <p class="text-xs text-gray-600">Total Crops: ${farmer.crops.length}</p>
            <p class="text-xs text-gray-600 capitalize mb-3">Primary Status: ${primaryStatus}</p>
            <a href="/farmers/${farmer.id}" style="display: block; margin-top: 0.5rem; padding: 0.375rem 0.75rem; background-color: #16a34a; color: white !important; text-align: center; font-size: 0.75rem; font-weight: 500; border-radius: 0.375rem; text-decoration: none;">
                View Farmer Profile
            </a>
        </div>
    `);
    
    return marker;
}

function showFarmDetails(farmer) {
    document.getElementById('farmInfoPanel').classList.remove('hidden');
    document.getElementById('farmFarmerName').textContent = farmer.name;
    document.getElementById('farmSize').textContent = `${farmer.farm_size || 'N/A'} ha`;
    document.getElementById('farmCrops').textContent = farmer.crops.length;
    
    // Populate crops list
    const cropsList = document.getElementById('farmCropsList');
    cropsList.innerHTML = '';
    
    if (farmer.crops.length > 0) {
        farmer.crops.forEach(crop => {
            const statusColors = {
                'planted': 'bg-blue-100 text-blue-800',
                'growing': 'bg-green-100 text-green-800',
                'harvested': 'bg-gray-100 text-gray-800',
                'failed': 'bg-red-100 text-red-800'
            };
            const colorClass = statusColors[crop.status] || 'bg-gray-100 text-gray-800';
            
            const plantingDate = crop.planting_date ? new Date(crop.planting_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
            const harvestDate = crop.expected_harvest_date ? new Date(crop.expected_harvest_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
            
            cropsList.innerHTML += `
                <div class="p-2 bg-white rounded border border-gray-200 text-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-gray-900">${crop.crop_name}</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium ${colorClass}">
                            ${crop.status.charAt(0).toUpperCase() + crop.status.slice(1)}
                        </span>
                    </div>
                    <p class="text-gray-600">${crop.variety || 'N/A'}</p>
                    <div class="mt-1 space-y-0.5 text-gray-500">
                        <p>Area: ${crop.area_planted || '0'} ha</p>
                        <p>Planted: ${plantingDate}</p>
                        <p>Harvest: ${harvestDate}</p>
                    </div>
                </div>
            `;
        });
    } else {
        cropsList.innerHTML = '<p class="text-xs text-gray-500 text-center py-2">No crops registered</p>';
    }
    
    // Update view farmer link
    document.getElementById('viewFarmerLink').href = `/farmers/${farmer.id}`;
}

function setupMapFilters() {
    const searchInput = document.getElementById('mapSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const sizeFilter = document.getElementById('sizeFilter');
    
    searchInput.addEventListener('input', filterMarkers);
    statusFilter.addEventListener('change', filterMarkers);
    sizeFilter.addEventListener('change', filterMarkers);
}

function filterMarkers() {
    const searchTerm = document.getElementById('mapSearchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const sizeFilter = document.getElementById('sizeFilter').value;
    
    const filtered = farmersData.filter(farmer => {
        // Name search
        if (searchTerm && !farmer.name.toLowerCase().includes(searchTerm)) {
            return false;
        }
        
        // Status filter
        if (statusFilter) {
            const hasCropWithStatus = farmer.crops.some(crop => crop.status === statusFilter);
            if (!hasCropWithStatus) return false;
        }
        
        // Size filter
        if (sizeFilter) {
            const farmSize = parseFloat(farmer.farm_size) || 0;
            if (sizeFilter === '0-2' && farmSize > 2) return false;
            if (sizeFilter === '2-5' && (farmSize <= 2 || farmSize > 5)) return false;
            if (sizeFilter === '5+' && farmSize <= 5) return false;
        }
        
        return true;
    });
    
    addFarmMarkers(filtered);
    
    // Fit bounds if there are markers
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        farmsMap.fitBounds(group.getBounds(), { padding: [50, 50], maxZoom: 13 });
    }
}

function resetMapFilters() {
    document.getElementById('mapSearchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sizeFilter').value = '';
    document.getElementById('farmInfoPanel').classList.add('hidden');
    
    addFarmMarkers(farmersData);
    
    // Fit bounds if there are markers
    if (allMarkers.length > 0) {
        const group = new L.featureGroup(allMarkers);
        farmsMap.fitBounds(group.getBounds(), { padding: [50, 50], maxZoom: 13 });
    }
}
</script>

<script type="application/json" id="dashboard-stats-json">
{!! json_encode([
  'stats' => $stats,
  'genderStats' => $genderStats,
  'activityStats' => $activityStats,
  'cropHectares' => $cropHectares,
  'topRiceVarieties' => $topRiceVarieties,
  'recentMonths' => $recentMonths,
]) !!}
</script>

<script>
// Dashboard Search Functionality
let searchTimeout;
const searchInput = document.getElementById('dashboardSearch');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();
    
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        searchResults.innerHTML = '';
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch(`/dashboard/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    }, 300);
});

function displaySearchResults(results) {
    if (results.length === 0) {
        searchResults.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">No results found</div>';
        searchResults.classList.remove('hidden');
        return;
    }
    
    const typeIcons = {
        farmer: `<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>`,
        crop: `<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>`,
        activity: `<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>`
    };
    
    let html = '<div class="divide-y divide-gray-200">';
    
    results.forEach(result => {
        html += `
            <a href="${result.url}" class="block p-4 hover:bg-gray-50 transition">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 mt-0.5">
                        ${typeIcons[result.type]}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">${result.title}</p>
                        <p class="text-xs text-gray-500 mt-0.5">${result.subtitle}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
        `;
    });
    
    html += '</div>';
    searchResults.innerHTML = html;
    searchResults.classList.remove('hidden');
}

// Hide search results when clicking outside
document.addEventListener('click', function(event) {
    if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
        searchResults.classList.add('hidden');
    }
});

// Show results again when focusing on search input if there's content
searchInput.addEventListener('focus', function() {
    if (this.value.trim().length >= 2 && searchResults.innerHTML) {
        searchResults.classList.remove('hidden');
    }
});
</script>

@vite('resources/js/dashboard.js')
@endpush
@endsection