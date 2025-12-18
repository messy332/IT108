@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('crops.update', $crop) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6 flex justify-center">
                <h1 class="text-3xl font-bold text-gray-900">EDIT CROP</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <!-- Farmer Selection -->
                <div class="md:col-span-4">
                    <label for="farmer_search" class="block text-sm font-medium text-gray-700 mb-2">Select Farmer</label>
                    <input type="text" id="farmer_search" placeholder="Search farmer by name or email..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 mb-2">
                    <select name="farmer_id" id="farmer_id" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('farmer_id') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                            @if($errors->has('farmer_id')) aria-invalid="true" @endif required>
                        <option value="">Choose a farmer</option>
                        @foreach($farmers as $farmer)
                            <option value="{{ $farmer->id }}" data-name="{{ strtolower($farmer->name) }}" data-email="{{ strtolower($farmer->email) }}" {{ old('farmer_id', $crop->farmer_id) == $farmer->id ? 'selected' : '' }}>
                                {{ $farmer->name }} ({{ $farmer->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('farmer_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Crop Information -->
                <div class="md:col-span-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Crop Information</h3>
                </div>
                
                <div>
                    <label for="crop_name" class="block text-sm font-medium text-gray-700 mb-2">Crop Name</label>
                    <input type="text" id="crop_name_display" value="{{ $crop->crop_name }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly disabled>
                    <input type="hidden" name="crop_name" id="crop_name" value="{{ $crop->crop_name }}">
                    @error('crop_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="variety" class="block text-sm font-medium text-gray-700 mb-2">Rice Variety</label>
                    <select name="variety" id="variety" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('variety') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                            @if($errors->has('variety')) aria-invalid="true" @endif required>
                        <option value="">Select Rice Variety</option>
                        
                        <optgroup label="A. Premium / Aromatic Market Varieties">
                            <option value="Princess Bea" {{ old('variety', $crop->variety) === 'Princess Bea' ? 'selected' : '' }}>Princess Bea</option>
                            <option value="Dinorado" {{ old('variety', $crop->variety) === 'Dinorado' ? 'selected' : '' }}>Dinorado</option>
                            <option value="Super Dinorado" {{ old('variety', $crop->variety) === 'Super Dinorado' ? 'selected' : '' }}>Super Dinorado</option>
                            <option value="Angelica" {{ old('variety', $crop->variety) === 'Angelica' ? 'selected' : '' }}>Angelica</option>
                            <option value="Super Angelica" {{ old('variety', $crop->variety) === 'Super Angelica' ? 'selected' : '' }}>Super Angelica</option>
                            <option value="Milagrosa" {{ old('variety', $crop->variety) === 'Milagrosa' ? 'selected' : '' }}>Milagrosa</option>
                            <option value="Harvester" {{ old('variety', $crop->variety) === 'Harvester' ? 'selected' : '' }}>Harvester</option>
                            <option value="Sinandomeng" {{ old('variety', $crop->variety) === 'Sinandomeng' ? 'selected' : '' }}>Sinandomeng</option>
                            <option value="Sinandomeng Special" {{ old('variety', $crop->variety) === 'Sinandomeng Special' ? 'selected' : '' }}>Sinandomeng Special</option>
                            <option value="Hasmine" {{ old('variety', $crop->variety) === 'Hasmine' ? 'selected' : '' }}>Hasmine (PhilRice aromatic)</option>
                            <option value="Jasmine" {{ old('variety', $crop->variety) === 'Jasmine' ? 'selected' : '' }}>Jasmine / Thai Jasmine</option>
                        </optgroup>
                        
                        <optgroup label="B. Regular Milled / Well-Milled Categories">
                            <option value="Regular Rice" {{ old('variety', $crop->variety) === 'Regular Rice' ? 'selected' : '' }}>Regular Rice</option>
                            <option value="Well-Milled Rice" {{ old('variety', $crop->variety) === 'Well-Milled Rice' ? 'selected' : '' }}>Well-Milled Rice</option>
                            <option value="Premium Rice" {{ old('variety', $crop->variety) === 'Premium Rice' ? 'selected' : '' }}>Premium Rice</option>
                            <option value="Blue Label" {{ old('variety', $crop->variety) === 'Blue Label' ? 'selected' : '' }}>Blue Label</option>
                            <option value="Red Label" {{ old('variety', $crop->variety) === 'Red Label' ? 'selected' : '' }}>Red Label</option>
                            <option value="Gold Label" {{ old('variety', $crop->variety) === 'Gold Label' ? 'selected' : '' }}>Gold Label</option>
                            <option value="Diamond Rice" {{ old('variety', $crop->variety) === 'Diamond Rice' ? 'selected' : '' }}>Diamond Rice</option>
                        </optgroup>
                        
                        <optgroup label="C. Glutinous / Sticky Rice (Malagkit)">
                            <option value="Malagkit White" {{ old('variety', $crop->variety) === 'Malagkit White' ? 'selected' : '' }}>Malagkit White</option>
                            <option value="Malagkit Black" {{ old('variety', $crop->variety) === 'Malagkit Black' ? 'selected' : '' }}>Malagkit Black</option>
                            <option value="Ominio" {{ old('variety', $crop->variety) === 'Ominio' ? 'selected' : '' }}>Ominio (Purple sticky rice)</option>
                            <option value="Diket" {{ old('variety', $crop->variety) === 'Diket' ? 'selected' : '' }}>Diket (Cordillera sticky rice)</option>
                        </optgroup>
                        
                        <optgroup label="D. Heirloom / Native Varieties">
                            <option value="Tinawon White" {{ old('variety', $crop->variety) === 'Tinawon White' ? 'selected' : '' }}>Tinawon (white)</option>
                            <option value="Tinawon Red" {{ old('variety', $crop->variety) === 'Tinawon Red' ? 'selected' : '' }}>Tinawon (red)</option>
                            <option value="Tinawon Pink" {{ old('variety', $crop->variety) === 'Tinawon Pink' ? 'selected' : '' }}>Tinawon (pink)</option>
                            <option value="Unoy" {{ old('variety', $crop->variety) === 'Unoy' ? 'selected' : '' }}>Unoy</option>
                            <option value="Unoy Red Rice" {{ old('variety', $crop->variety) === 'Unoy Red Rice' ? 'selected' : '' }}>Unoy red rice</option>
                            <option value="Balatinaw" {{ old('variety', $crop->variety) === 'Balatinaw' ? 'selected' : '' }}>Balatinaw (black)</option>
                            <option value="Kintoman" {{ old('variety', $crop->variety) === 'Kintoman' ? 'selected' : '' }}>Kintoman</option>
                        </optgroup>
                        
                        <optgroup label="E. Others">
                            <option value="others" {{ old('variety', $crop->variety) === 'others' ? 'selected' : '' }}>Others (Please specify)</option>
                        </optgroup>
                    </select>
                    @error('variety')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Custom Variety Input (hidden by default) -->
                <div id="custom_variety_container" class="hidden md:col-span-4">
                    <label for="custom_variety" class="block text-sm font-medium text-gray-700 mb-2">Specify Rice Variety <span class="text-red-500">*</span></label>
                    <input type="text" name="custom_variety" id="custom_variety" value="{{ old('custom_variety', $crop->variety && !in_array($crop->variety, ['Princess Bea', 'Dinorado', 'Super Dinorado', 'Angelica', 'Super Angelica', 'Milagrosa', 'Harvester', 'Sinandomeng', 'Sinandomeng Special', 'Hasmine', 'Jasmine', 'Regular Rice', 'Well-Milled Rice', 'Premium Rice', 'Blue Label', 'Red Label', 'Gold Label', 'Diamond Rice', 'Malagkit White', 'Malagkit Black', 'Ominio', 'Diket', 'Tinawon White', 'Tinawon Red', 'Tinawon Pink', 'Unoy', 'Unoy Red Rice', 'Balatinaw', 'Kintoman']) ? $crop->variety : '') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('custom_variety') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                           placeholder="Enter the rice variety name"
                           @if($errors->has('custom_variety')) aria-invalid="true" @endif>
                    @error('custom_variety')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="area_planted" class="block text-sm font-medium text-gray-700 mb-2">Area Planted (hectares)</label>
                    <input type="number" name="area_planted" id="area_planted" value="{{ old('area_planted', $crop->area_planted) }}" step="0.01" min="0.01"
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('area_planted') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                           @if($errors->has('area_planted')) aria-invalid="true" @endif required>
                    @error('area_planted')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="season" class="block text-sm font-medium text-gray-700 mb-2">Season</label>
                    <select name="season" id="season" 
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('season') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                            @if($errors->has('season')) aria-invalid="true" @endif required>
                        <option value="">Select Season</option>
                        <option value="wet" {{ old('season', $crop->season) === 'wet' ? 'selected' : '' }}>Wet Season</option>
                        <option value="dry" {{ old('season', $crop->season) === 'dry' ? 'selected' : '' }}>Dry Season</option>
                        <option value="year-round" {{ old('season', $crop->season) === 'year-round' ? 'selected' : '' }}>Year-round</option>
                    </select>
                    @error('season')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <input type="text" id="status_display" 
                           value="{{ ucfirst($crop->status) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly disabled>
                    <input type="hidden" name="status" id="status" value="{{ $crop->status }}">
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dates -->
                <div class="md:col-span-4 mt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Planting Schedule</h3>
                </div>

                <div>
                    <label for="planting_date" class="block text-sm font-medium text-gray-700 mb-2">Planting Date</label>
                    <input type="date" name="planting_date" id="planting_date" value="{{ old('planting_date', $crop->planting_date->format('Y-m-d')) }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('planting_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}" 
                           @if($errors->has('planting_date')) aria-invalid="true" @endif required>
                    @error('planting_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="plantingHint" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="expected_harvest_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Harvest Date</label>
                    <input type="date" id="expected_harvest_date_display" 
                           value="{{ $crop->expected_harvest_date ? $crop->expected_harvest_date->format('Y-m-d') : '' }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly disabled>
                    <input type="hidden" name="expected_harvest_date" id="expected_harvest_date" 
                           value="{{ $crop->expected_harvest_date ? $crop->expected_harvest_date->format('Y-m-d') : '' }}">
                    @error('expected_harvest_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="actual_harvest_date" class="block text-sm font-medium text-gray-700 mb-2">Actual Harvest Date</label>
                    <input type="date" name="actual_harvest_date" id="actual_harvest_date" 
                           value="{{ old('actual_harvest_date', $crop->actual_harvest_date ? $crop->actual_harvest_date->format('Y-m-d') : '') }}" 
                           class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('actual_harvest_date') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}">
                    @error('actual_harvest_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="actualHarvestHint" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <!-- Yield Information -->
                <div class="md:col-span-4 mt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Yield Information</h3>
                </div>

                <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div>
                        <label for="expected_yield" class="block text-sm font-medium text-gray-700 mb-2">Expected Yield (kg)</label>
                        <input type="text" id="expected_yield_display" value="{{ $crop->expected_yield ? number_format($crop->expected_yield, 2) : '' }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                               readonly disabled>
                        <input type="hidden" name="expected_yield" id="expected_yield" value="{{ $crop->expected_yield }}">
                        @error('expected_yield')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="actual_yield" class="block text-sm font-medium text-gray-700 mb-2">Actual Yield (kg)</label>
                        <input type="number" name="actual_yield" id="actual_yield" value="{{ old('actual_yield', $crop->actual_yield) }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('actual_yield') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}">
                        @error('actual_yield')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harvest_image" class="block text-sm font-medium text-gray-700 mb-2">Proof Image</label>
                        <input type="file" name="harvest_image" id="harvest_image" accept="image/*"
                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:border-blue-500 focus:ring-blue-500 {{ $errors->has('harvest_image') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300' }}">
                        @error('harvest_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($crop->harvest_image)
                    <div class="md:col-span-4 mt-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm font-medium text-gray-700 mb-3">Current Proof Image:</p>
                        <img src="{{ asset('storage/' . $crop->harvest_image) }}" alt="Harvest proof" class="h-40 w-40 object-cover rounded border border-gray-300">
                        <label class="flex items-center mt-3">
                            <input type="checkbox" name="remove_harvest_image" class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-600">Remove image</span>
                        </label>
                    </div>
                @endif


            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('crops.show', $crop) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Crop
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/crops-edit.js')
@endpush