@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-lg shadow p-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 uppercase">Farmer Profile</h1>
        </div>

        <form action="{{ route('farmer.profile.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Personal Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-900 mb-[-10px]">Personal Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-4">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               autocomplete="name" spellcheck="false" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500 cursor-not-allowed" 
                               readonly>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               inputmode="numeric" maxlength="15" required>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">Birth Date <span class="text-red-500">*</span></label>
                        <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" 
                               min="{{ now()->subYears(75)->format('Y-m-d') }}" 
                               max="{{ now()->subYears(18)->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               required>
                        @error('birthdate')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('age')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Age -->
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age <span class="text-red-500">*</span></label>
                        <input type="number" id="age" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50" readonly>
                    </div>

                    <!-- Sex -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Sex <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                required>
                            <option value="">Select Sex</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-3">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="1" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Farm Information Section -->
            <div class="mb-8">
                <h2 class="text-lg font-bold text-black mb-[-10px]">Farm Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-4">
                    <!-- Farm Size -->
                    <div>
                        <label for="farm_size" class="block text-sm font-medium text-gray-700 mb-2">Farm Size (hectares) <span class="text-red-500">*</span></label>
                        <input type="text" name="farm_size" id="farm_size" value="{{ old('farm_size') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               inputmode="decimal" placeholder="e.g. 2.50" required>
                        @error('farm_size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Farm Type -->
                    <div>
                        <label for="farm_type" class="block text-sm font-medium text-gray-700 mb-2">Farm Type <span class="text-red-500">*</span></label>
                        <select name="farm_type" id="farm_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed" 
                                readonly disabled>
                            <option value="crop" selected>Crop</option>
                        </select>
                        <input type="hidden" name="farm_type" value="crop">
                        @error('farm_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Supporting Document -->
                    <div>
                        <label for="supporting_document" class="block text-sm font-medium text-gray-700 mb-2">Document <span class="text-red-500">*</span></label>
                        <input type="file" name="supporting_document" id="supporting_document" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        @error('supporting_document')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB)</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium">
                    Register Farmer
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@vite('resources/js/farmer-profile-create.js')
@endpush
@endsection
