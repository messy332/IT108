@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Farmers Management</h1>
    <div class="mb-6 space-y-3">
        <form id="farmers-filter-form" method="GET" action="{{ route('farmers.index') }}">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search farmers..." class="w-full pl-9 pr-3 py-2 border border-gray-900 hover:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    </span>
                </div>
                
                <select name="gender" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:w-40">
                    <option value="">All Genders</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                </select>

                <select name="age_range" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:w-40">
                    <option value="">All Ages</option>
                    <option value="18-30" {{ request('age_range') === '18-30' ? 'selected' : '' }}>18-30 years</option>
                    <option value="31-45" {{ request('age_range') === '31-45' ? 'selected' : '' }}>31-45 years</option>
                    <option value="46-60" {{ request('age_range') === '46-60' ? 'selected' : '' }}>46-60 years</option>
                    <option value="60+" {{ request('age_range') === '60+' ? 'selected' : '' }}>60+ years</option>
                </select>

                <select name="farm_size_range" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:w-44">
                    <option value="">All Farm Sizes</option>
                    <option value="0-1" {{ request('farm_size_range') === '0-1' ? 'selected' : '' }}>0-1 hectares</option>
                    <option value="1-3" {{ request('farm_size_range') === '1-3' ? 'selected' : '' }}>1-3 hectares</option>
                    <option value="3-5" {{ request('farm_size_range') === '3-5' ? 'selected' : '' }}>3-5 hectares</option>
                    <option value="5+" {{ request('farm_size_range') === '5+' ? 'selected' : '' }}>5+ hectares</option>
                </select>
            </div>
        </form>

        <div class="flex flex-wrap gap-2">
            <button type="submit" form="farmers-filter-form" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium whitespace-nowrap">
                Apply Filters
            </button>
            @if(request()->hasAny(['q', 'gender', 'age_range', 'farm_size_range']))
                <a href="{{ route('farmers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md font-medium text-center whitespace-nowrap">
                    Clear
                </a>
            @endif
            <a href="{{ route('farmers.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-medium text-center whitespace-nowrap">
                Add New Farmer
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age/Sex</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Crops</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($farmers as $farmer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $farmer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $farmer->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $farmer->age }} years old</div>
                                <div class="text-sm text-gray-500 capitalize">{{ $farmer->gender }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $farmer->phone }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($farmer->address, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $farmer->farm_size }} hectares</div>
                                <div class="text-sm text-gray-500 capitalize">{{ $farmer->farm_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $farmer->crops_count }} total</div>
                                <div class="text-sm text-gray-500">{{ $farmer->active_crops_count }} active</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <select class="status-dropdown px-6 py-2 text-xs font-semibold rounded-md border border-gray-300 cursor-pointer transition-all
                                    {{ $farmer->status === 'active' ? 'bg-green-50 text-green-700 border-green-300 hover:bg-green-100' : 'bg-red-50 text-red-700 border-red-300 hover:bg-red-100' }}"
                                    data-farmer-id="{{ $farmer->id }}"
                                    onchange="updateFarmerStatus(this)">
                                    <option value="active" {{ $farmer->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $farmer->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('farmers.show', $farmer) }}" class="text-blue-600 hover:text-blue-900 mr-3 inline-block" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                                <a href="{{ route('farmers.show', $farmer) }}" class="text-green-600 hover:text-green-900 mr-3 inline-block" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900 inline-block" title="Delete"
                                        onclick="openDeleteModal('{{ $farmer->id }}', '{{ $farmer->name }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $farmer->id }}" action="{{ route('farmers.destroy', $farmer) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No farmers found. <a href="{{ route('farmers.create') }}" class="text-green-600 hover:text-green-900">Add the first farmer</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($farmers->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $farmers->appends(['q' => request('q')])->links() }}
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
            <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Delete Farmer</h3>
            <p class="text-sm text-center text-gray-500 mb-1">Are you sure you want to delete this farmer?</p>
            <p id="deleteItemName" class="text-sm text-center font-medium text-gray-700 mb-4"></p>
            <p class="text-xs text-center text-red-500 mb-6">This will also delete all associated crops and progress logs. This action cannot be undone.</p>
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

@push('scripts')
<script>
function updateFarmerStatus(selectElement) {
    const farmerId = selectElement.dataset.farmerId;
    const newStatus = selectElement.value;
    const originalStatus = selectElement.options[selectElement.selectedIndex].value;

    fetch(`/farmers/${farmerId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the dropdown styling based on new status
            if (newStatus === 'active') {
                selectElement.className = 'status-dropdown px-4 py-2 text-xs font-semibold rounded-md border border-green-300 cursor-pointer transition-all bg-green-50 text-green-700 hover:bg-green-100';
            } else {
                selectElement.className = 'status-dropdown px-4 py-2 text-xs font-semibold rounded-md border border-red-300 cursor-pointer transition-all bg-red-50 text-red-700 hover:bg-red-100';
            }
            
            // Show success message
            showNotification('Status updated successfully!', 'success');
        } else {
            selectElement.value = originalStatus;
            showNotification(data.message || 'Failed to update status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        selectElement.value = originalStatus;
        showNotification('An error occurred while updating the status', 'error');
    });
}

function showNotification(message, type) {
    const alertClass = type === 'success' 
        ? 'bg-green-100 border border-green-400 text-green-700' 
        : 'bg-red-100 border border-red-400 text-red-700';
    
    const notification = document.createElement('div');
    notification.className = `${alertClass} px-4 py-3 rounded fixed top-4 right-4 z-50`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush
@endsection