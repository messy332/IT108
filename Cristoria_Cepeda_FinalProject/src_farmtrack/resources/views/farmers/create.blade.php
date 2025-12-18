@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-lg shadow p-6">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('farmers.store') }}" method="POST" enctype="multipart/form-data" class="" novalidate>
            @csrf
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 flex justify-center">FARMER PROFILE</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <!-- Personal Information -->
                <div class="md:col-span-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-[-10px]">Personal Information</h3>
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           autocomplete="name" spellcheck="false">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <p id="nameHint" class="text-gray-500 text-xs mt-1">Letters only. Words separated by a single space. Each word capitalized.</p> --}}
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" inputmode="numeric" maxlength="15">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <p class="text-gray-500 text-xs mt-1">Digits only, 10–15 digits.</p> --}}
                </div>

                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">Birth Date <span class="text-red-500">*</span></label>
                    <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" 
                           min="{{ now()->subYears(75)->format('Y-m-d') }}"
                           max="{{ now()->subYears(18)->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('birthdate')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="birthdateHint" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age <span class="text-red-500">*</span></label>
                    <input type="number" name="age" id="age" value="{{ old('age') }}" min="18" max="75" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50">
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Sex <span class="text-red-500">*</span></label>
                    <select name="gender" id="gender" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Sex</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" id="address" rows="1" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Farm Information -->
                <div class="md:col-span-4 mt-0">
                    <h3 class="text-lg font-bold text-black mb-[-10px]">Farm Information</h3>
                </div>

                <div>
                    <label for="farm_size" class="block text-sm font-medium text-gray-700 mb-2">Farm Size (hectares) <span class="text-red-500">*</span></label>
                    <input type="text" name="farm_size" id="farm_size" value="{{ old('farm_size') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" inputmode="decimal" placeholder="e.g. 2.50">
                    @error('farm_size')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    {{-- <p class="text-gray-500 text-xs mt-1">Number between 0.01 and 10000, up to 2 decimals.</p> --}}
                </div>

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

                <div>
                    <label for="supporting_document" class="block text-sm font-medium text-gray-700 mb-2">Document <span class="text-red-500">*</span></label>
                    <input type="file" name="supporting_document" id="supporting_document" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    @error('supporting_document')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB)</p>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('farmers.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Register Farmer
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
@vite('resources/js/farmers-create.js')
@endpush
@endsection