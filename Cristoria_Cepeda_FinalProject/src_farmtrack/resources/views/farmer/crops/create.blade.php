@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    

    <div class="bg-white rounded-lg shadow p-6">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('farmer.crops.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6 flex justify-center">
                <h1 class="text-3xl font-bold text-gray-900">CROP</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <!-- Crop Information -->
                <div class="md:col-span-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Crop Information</h3>
                </div>
                
                <div>
                    <label for="crop_name" class="block text-sm font-medium text-gray-700 mb-2">Crop Name <span class="text-red-500">*</span></label>
                    <input type="text" id="crop_name_display" value="Rice" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly disabled>
                    <input type="hidden" name="crop_name" id="crop_name" value="Rice">
                    @error('crop_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <p class="text-gray-500 text-xs mt-1">Crop type is set to Rice</p> --}}
                </div>

                <div>
                    <label for="variety" class="block text-sm font-medium text-gray-700 mb-2">Rice Variety <span class="text-red-500">*</span></label>
                    <select name="variety" id="variety" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('variety') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                            @if($errors->has('variety')) aria-invalid="true" @endif>
                        <option value="">Select Rice Variety</option>
                        
                        <optgroup label="A. Premium / Aromatic Market Varieties">
                            <option value="Princess Bea" {{ old('variety') === 'Princess Bea' ? 'selected' : '' }}>Princess Bea</option>
                            <option value="Dinorado" {{ old('variety') === 'Dinorado' ? 'selected' : '' }}>Dinorado</option>
                            <option value="Super Dinorado" {{ old('variety') === 'Super Dinorado' ? 'selected' : '' }}>Super Dinorado</option>
                            <option value="Angelica" {{ old('variety') === 'Angelica' ? 'selected' : '' }}>Angelica</option>
                            <option value="Super Angelica" {{ old('variety') === 'Super Angelica' ? 'selected' : '' }}>Super Angelica</option>
                            <option value="Milagrosa" {{ old('variety') === 'Milagrosa' ? 'selected' : '' }}>Milagrosa</option>
                            <option value="Harvester" {{ old('variety') === 'Harvester' ? 'selected' : '' }}>Harvester</option>
                            <option value="Sinandomeng" {{ old('variety') === 'Sinandomeng' ? 'selected' : '' }}>Sinandomeng</option>
                            <option value="Sinandomeng Special" {{ old('variety') === 'Sinandomeng Special' ? 'selected' : '' }}>Sinandomeng Special</option>
                            <option value="Hasmine" {{ old('variety') === 'Hasmine' ? 'selected' : '' }}>Hasmine (PhilRice aromatic)</option>
                            <option value="Jasmine" {{ old('variety') === 'Jasmine' ? 'selected' : '' }}>Jasmine / Thai Jasmine</option>
                        </optgroup>
                        
                        <optgroup label="B. Regular Milled / Well-Milled Categories">
                            <option value="Regular Rice" {{ old('variety') === 'Regular Rice' ? 'selected' : '' }}>Regular Rice</option>
                            <option value="Well-Milled Rice" {{ old('variety') === 'Well-Milled Rice' ? 'selected' : '' }}>Well-Milled Rice</option>
                            <option value="Premium Rice" {{ old('variety') === 'Premium Rice' ? 'selected' : '' }}>Premium Rice</option>
                            <option value="Blue Label" {{ old('variety') === 'Blue Label' ? 'selected' : '' }}>Blue Label</option>
                            <option value="Red Label" {{ old('variety') === 'Red Label' ? 'selected' : '' }}>Red Label</option>
                            <option value="Gold Label" {{ old('variety') === 'Gold Label' ? 'selected' : '' }}>Gold Label</option>
                            <option value="Diamond Rice" {{ old('variety') === 'Diamond Rice' ? 'selected' : '' }}>Diamond Rice</option>
                        </optgroup>
                        
                        <optgroup label="C. Glutinous / Sticky Rice (Malagkit)">
                            <option value="Malagkit White" {{ old('variety') === 'Malagkit White' ? 'selected' : '' }}>Malagkit White</option>
                            <option value="Malagkit Black" {{ old('variety') === 'Malagkit Black' ? 'selected' : '' }}>Malagkit Black</option>
                            <option value="Ominio" {{ old('variety') === 'Ominio' ? 'selected' : '' }}>Ominio (Purple sticky rice)</option>
                            <option value="Diket" {{ old('variety') === 'Diket' ? 'selected' : '' }}>Diket (Cordillera sticky rice)</option>
                        </optgroup>
                        
                        <optgroup label="D. Heirloom / Native Varieties">
                            <option value="Tinawon White" {{ old('variety') === 'Tinawon White' ? 'selected' : '' }}>Tinawon (white)</option>
                            <option value="Tinawon Red" {{ old('variety') === 'Tinawon Red' ? 'selected' : '' }}>Tinawon (red)</option>
                            <option value="Tinawon Pink" {{ old('variety') === 'Tinawon Pink' ? 'selected' : '' }}>Tinawon (pink)</option>
                            <option value="Unoy" {{ old('variety') === 'Unoy' ? 'selected' : '' }}>Unoy</option>
                            <option value="Unoy Red Rice" {{ old('variety') === 'Unoy Red Rice' ? 'selected' : '' }}>Unoy red rice</option>
                            <option value="Balatinaw" {{ old('variety') === 'Balatinaw' ? 'selected' : '' }}>Balatinaw (black)</option>
                            <option value="Kintoman" {{ old('variety') === 'Kintoman' ? 'selected' : '' }}>Kintoman</option>
                        </optgroup>
                        
                        <optgroup label="E. Others">
                            <option value="others" {{ old('variety') === 'others' ? 'selected' : '' }}>Others (Please specify)</option>
                        </optgroup>
                    </select>
                    @error('variety')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Custom Variety Input (hidden by default) -->
                <div id="custom_variety_container" class="hidden md:col-span-4">
                    <label for="custom_variety" class="block text-sm font-medium text-gray-700 mb-2">Specify Rice Variety <span class="text-red-500">*</span></label>
                    <input type="text" name="custom_variety" id="custom_variety" value="{{ old('custom_variety') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('custom_variety') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                           placeholder="Enter the rice variety name"
                           @if($errors->has('custom_variety')) aria-invalid="true" @endif>
                    @error('custom_variety')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="area_planted" class="block text-sm font-medium text-gray-700 mb-2">Area Planted (hectares) <span class="text-red-500">*</span></label>
                    <input type="text" name="area_planted" id="area_planted" value="{{ old('area_planted') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('area_planted') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                           inputmode="decimal" placeholder="e.g. 2.50"
                           @if($errors->has('area_planted')) aria-invalid="true" @endif>
                    @error('area_planted')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <p class="text-gray-500 text-xs mt-1">Numbers only, up to 2 decimal places</p> --}}
                </div>

                <div>
                    <label for="season" class="block text-sm font-medium text-gray-700 mb-2">Season <span class="text-red-500">*</span></label>
                    <select name="season" id="season" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('season') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                            @if($errors->has('season')) aria-invalid="true" @endif>
                        <option value="">Select Season</option>
                        <option value="wet" {{ old('season') === 'wet' ? 'selected' : '' }}>Wet Season</option>
                        <option value="dry" {{ old('season') === 'dry' ? 'selected' : '' }}>Dry Season</option>
                        <option value="year-round" {{ old('season') === 'year-round' ? 'selected' : '' }}>Year-round</option>
                    </select>
                    @error('season')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="md:col-span-4 mt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Planting Schedule</h3>
                </div>

                <div>
                    <label for="planting_date" class="block text-sm font-medium text-gray-700 mb-2">Planting Date <span class="text-red-500">*</span></label>
                    <input type="date" name="planting_date" id="planting_date" value="{{ old('planting_date') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('planting_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                           @if($errors->has('planting_date')) aria-invalid="true" @endif>
                    @error('planting_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="plantingHint" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="expected_harvest_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Harvest Date <span class="text-red-500">*</span></label>
                    <input type="date" name="expected_harvest_date" id="expected_harvest_date" value="{{ old('expected_harvest_date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly>
                </div>

                <!-- Yield Expectations -->
                <div>
                    <label for="expected_yield" class="block text-sm font-medium text-gray-700 mb-2">Expected Yield (kg) <span class="text-red-500">*</span></label>
                    <input type="text" name="expected_yield" id="expected_yield" value="{{ old('expected_yield') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly>
                </div>
            </div>

            {{-- <!-- Crop Information Section -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">📋 Crop Information</h3>
                <div class="text-sm text-gray-700 space-y-2">
                    <p><strong>Rice Growing Cycle:</strong> Typically 90-150 days depending on variety</p>
                    <p><strong>Expected Yield:</strong> Average 3-5 tons per hectare for most varieties</p>
                    <p><strong>Season Guide:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li><strong>Wet Season:</strong> June to November (monsoon period)</li>
                        <li><strong>Dry Season:</strong> December to May (irrigation required)</li>
                        <li><strong>Year-round:</strong> Continuous planting with proper irrigation</li>
                    </ul>
                    <p class="mt-3 text-xs text-gray-600">
                        <strong>Note:</strong> Expected harvest date and yield are automatically calculated based on your selected variety and area planted.
                    </p>
                </div>
            </div> --}}

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('farmer.crops.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Register Crop
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/crops-create.js')
@endpush
