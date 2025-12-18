@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
   

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('progress-logs.update', $progressLog) }}" method="POST" id="progressLogForm" novalidate>
            @csrf
            @method('PUT')
            <div class="mb-6 flex justify-center">
                <h1 class="text-3xl font-bold text-gray-900">EDIT PROGRESS LOG</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <!-- Crop Selection -->
                <div class="md:col-span-4">
                    <label for="crop_id" class="block text-sm font-medium text-gray-700 mb-2">Select Crop <span class="text-red-500">*</span></label>
                    <select name="crop_id" id="crop_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Choose a crop</option>
                        @foreach($crops as $crop)
                            <option 
                                value="{{ $crop->id }}" 
                                data-planting="{{ $crop->planting_date ? $crop->planting_date->format('Y-m-d') : '' }}"
                                data-actual="{{ $crop->actual_harvest_date ? $crop->actual_harvest_date->format('Y-m-d') : '' }}"
                                {{ old('crop_id', $progressLog->crop_id) == $crop->id ? 'selected' : '' }}
                            >
                                {{ $crop->crop_name }} ({{ $crop->variety }}) - {{ $crop->farmer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('crop_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="cropError" class="text-red-500 text-sm mt-1 hidden">This input field is required</p>
                </div>

                <!-- Log Information -->
                <div class="md:col-span-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Activity Information</h3>
                </div>
                
                <div>
                    <label for="log_date" class="block text-sm font-medium text-gray-700 mb-2">Activity Date <span class="text-red-500">*</span></label>
                    <input type="date" name="log_date" id="log_date" value="{{ old('log_date', $progressLog->log_date->format('Y-m-d')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly>
                    @error('log_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="activity_type" class="block text-sm font-medium text-gray-700 mb-2">Activity Type <span class="text-red-500">*</span></label>
                    <select name="activity_type" id="activity_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                            disabled>
                        <option value="">Select Activity</option>
                        @foreach(\App\Models\ProgressLog::getActivityTypes() as $key => $label)
                            <option value="{{ $key }}" {{ old('activity_type', $progressLog->activity_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="activity_type" value="{{ old('activity_type', $progressLog->activity_type) }}">
                    @error('activity_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="activityError" class="text-red-500 text-sm mt-1 hidden">This input field is required</p>
                </div>

                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Cost per Activity (₱) <span class="text-red-500">*</span></label>
                    <input type="text" name="cost" id="cost" value="{{ old('cost', $progressLog->cost) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           inputmode="decimal" placeholder="0.00">
                    @error('cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="costError" class="text-red-500 text-sm mt-1 hidden">This input field is required</p>
                </div>

                <div>
                    <label for="weather_condition" class="block text-sm font-medium text-gray-700 mb-2">Weather Condition <span class="text-red-500">*</span></label>
                    <select name="weather_condition" id="weather_condition" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select Weather</option>
                        @foreach(\App\Models\ProgressLog::getWeatherConditions() as $key => $label)
                            <option value="{{ $key }}" {{ old('weather_condition', $progressLog->weather_condition) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('weather_condition')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="weatherError" class="text-red-500 text-sm mt-1 hidden">This input field is required</p>
                </div>

                <div class="md:col-span-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('description', $progressLog->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="descError" class="text-red-500 text-sm mt-1 hidden">This input field is required</p>
                    <p id="descHint" class="text-red-500 text-sm mt-1 hidden"></p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-gray-500 text-xs"><span id="descCount">0</span>/1000</p>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="md:col-span-4 mt-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-0">Additional Details</h3>
                </div>

                <div>
                    <label for="growth_stage" class="block text-sm font-medium text-gray-700 mb-2">Growth Stage (1-10) <span class="text-red-500">*</span></label>
                    <input type="number" name="growth_stage" id="growth_stage" value="{{ old('growth_stage', $progressLog->growth_stage) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                           readonly min="1" max="10">
                    @error('growth_stage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-3">
                    <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">Observations <span class="text-red-500">*</span></label>
                    <textarea name="observations" id="observations" rows="1" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('observations', $progressLog->observations) }}</textarea>
                    @error('observations')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="obsError" class="text-red-500 text-sm mt-1 hidden">This input field is required</p>
                    <p id="obsHint" class="text-red-500 text-sm mt-1 hidden"></p>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-gray-500 text-xs"><span id="obsCount">0</span>/1000</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('progress-logs.show', $progressLog) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    Update Log
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
@vite('resources/js/progress-logs-edit.js')
<script>
(function() {
    function validateProgressLogForm(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var isValid = true;
        
        function showError(id, message) {
            var el = document.getElementById(id);
            if (el) {
                el.textContent = message;
                el.classList.remove('hidden');
            }
        }
        
        function hideError(id) {
            var el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
            }
        }
        
        function addBorderError(el) {
            if (el) el.classList.add('border-red-500');
        }
        
        function removeBorderError(el) {
            if (el) el.classList.remove('border-red-500');
        }
        
        // Crop validation
        var cropSel = document.getElementById('crop_id');
        if (!cropSel || !cropSel.value) {
            showError('cropError', 'This input field is required');
            addBorderError(cropSel);
            isValid = false;
        } else {
            hideError('cropError');
            removeBorderError(cropSel);
        }
        
        // Cost validation
        var cost = document.getElementById('cost');
        if (!cost || !cost.value || cost.value.trim() === '') {
            showError('costError', 'This input field is required');
            addBorderError(cost);
            isValid = false;
        } else {
            hideError('costError');
            removeBorderError(cost);
        }
        
        // Weather validation
        var weatherSel = document.getElementById('weather_condition');
        if (!weatherSel || !weatherSel.value) {
            showError('weatherError', 'This input field is required');
            addBorderError(weatherSel);
            isValid = false;
        } else {
            hideError('weatherError');
            removeBorderError(weatherSel);
        }
        
        // Description validation
        var desc = document.getElementById('description');
        if (!desc || !desc.value || desc.value.trim() === '') {
            showError('descError', 'This input field is required');
            addBorderError(desc);
            isValid = false;
        } else {
            hideError('descError');
            removeBorderError(desc);
        }
        
        // Observations validation
        var obs = document.getElementById('observations');
        if (!obs || !obs.value || obs.value.trim() === '') {
            showError('obsError', 'This input field is required');
            addBorderError(obs);
            isValid = false;
        } else {
            hideError('obsError');
            removeBorderError(obs);
        }
        
        if (isValid) {
            document.getElementById('progressLogForm').submit();
        } else {
            var firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        return false;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        var submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.addEventListener('click', validateProgressLogForm);
        }
        
        var form = document.getElementById('progressLogForm');
        if (form) {
            form.addEventListener('submit', validateProgressLogForm);
        }
    });
})();
</script>
@endpush