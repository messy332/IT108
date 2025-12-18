@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <a href="{{ route('farmers.index') }}" class="text-gray-900 hover:underline font-normal text-md">
            ← Back to Farmers
        </a>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $farmer->name }}</h1>
            <p class="text-gray-600 mt-1">Farmer Profile & Crop Monitoring</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('farmers.edit', $farmer) }}" 
                class="text-black px-4 py-2 font-medium hover:underline">
                Edit Profile
            </a>
            <a href="{{ route('crops.create') }}?farmer_id={{ $farmer->id }}" 
                class="text-black px-4 py-2 font-medium hover:underline">
                Add Crop
            </a>
            <button type="button" onclick="openDeleteModal()" class="text-red-600 px-4 py-2 font-medium hover:underline">
                Delete
            </button>
            <form id="delete-form" action="{{ route('farmers.destroy', $farmer) }}" method="POST" class="hidden">
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
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Farmer Information -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Full Name</label>
                        <p class="text-gray-900">{{ $farmer->name }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Age & Gender</label>
                        <p class="text-gray-900">{{ $farmer->age }} years old • {{ ucfirst($farmer->gender) }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Birth Date</label>
                        <p class="text-gray-900">{{ $farmer->birthdate->format('F j, Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 break-all">{{ $farmer->email }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone</label>
                        <p class="text-gray-900">{{ $farmer->phone ?: '—' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900">{{ $farmer->address ?: '—' }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $farmer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($farmer->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Farm Information -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Farm Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Farm Size</label>
                        <p class="text-gray-900">{{ $farmer->farm_size }} hectares</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Farm Type</label>
                        <p class="text-gray-900 capitalize">{{ $farmer->farm_type ?: '—' }}</p>
                    </div>
                </div>
            </div>

            <!-- Farm Statistics -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Farm Statistics</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Crops</span>
                        <span class="font-semibold">{{ $farmer->crops->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Active Crops</span>
                        <span class="font-semibold">{{ $farmer->activeCrops->count() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Yield</span>
                        <span class="font-semibold">{{ number_format($farmer->total_yield, 2) }} kg</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Avg Growth Stage</span>
                        <span class="font-semibold">{{ $farmer->average_growth_stage }}/10</span>
                    </div>
                </div>
            </div>

            <!-- Supporting Document -->
            @if($farmer->supporting_document)
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Supporting Document</h2>
                    <p class="text-sm text-gray-600 mb-4">Proof of farm ownership</p>
                    <a href="{{ asset('storage/' . $farmer->supporting_document) }}" 
                       target="_blank" 
                       class="text-blue-600 hover:text-blue-900 font-medium">
                        View Document
                    </a>
                </div>
            @endif
        </div>

        <!-- Crops Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Crops Overview</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Crop Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Area</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Yield</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($farmer->crops as $crop)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $crop->crop_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $crop->variety }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $crop->area_planted }} ha
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @switch($crop->status)
                                                @case('planted') bg-blue-100 text-blue-800 @break
                                                @case('growing') bg-green-100 text-green-800 @break
                                                @case('harvested') bg-gray-100 text-gray-800 @break
                                                @case('failed') bg-red-100 text-red-800 @break
                                            @endswitch">
                                            {{ ucfirst($crop->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $crop->progress_percentage }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($crop->actual_yield)
                                            {{ number_format($crop->actual_yield, 2) }} kg
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('crops.show', $crop) }}" class="text-blue-600 hover:text-blue-900 inline-block" title="View">
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
                                        No crops registered yet. 
                                        <a href="{{ route('crops.create') }}?farmer_id={{ $farmer->id }}" class="text-blue-600 hover:text-blue-900">Add the first crop</a>
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
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Delete Farmer</h3>
            <p class="text-sm text-center text-gray-500 mb-1">Are you sure you want to delete this farmer?</p>
            <p class="text-sm text-center font-medium text-gray-700 mb-4">{{ $farmer->name }}</p>
            <p class="text-xs text-center text-red-500 mb-6">This will also delete all associated crops and progress logs. This action cannot be undone.</p>
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
