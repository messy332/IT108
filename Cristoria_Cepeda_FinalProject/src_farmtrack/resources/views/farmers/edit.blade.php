@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden">
        <div class="px-8 py-6">
            <h1 class="text-3xl font-bold text-gray-900 text-center mb-8">EDIT FARMER PROFILE</h1>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-8 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('farmers.update', $farmer) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            
            <!-- Personal Information Section -->
            <div class="px-8 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Row 1: Full Name, Email -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $farmer->name) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                               autocomplete="name" spellcheck="false">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $farmer->email) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row 2: Phone Number, Birth Date -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $farmer->phone) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" inputmode="numeric" maxlength="15">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birthdate" class="block text-sm font-semibold text-gray-700 mb-2">Birth Date <span class="text-red-500">*</span></label>
                        <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $farmer->birthdate->format('Y-m-d')) }}" 
                               min="{{ now()->subYears(75)->format('Y-m-d') }}"
                               max="{{ now()->subYears(18)->format('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        @error('birthdate')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p id="birthdateHint" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    <!-- Row 3: Age, Sex -->
                    <div>
                        <label for="age" class="block text-sm font-semibold text-gray-700 mb-2">Age <span class="text-red-500">*</span></label>
                        <input type="number" name="age" id="age" value="{{ old('age', $farmer->age) }}" min="18" max="75" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">Sex <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Select Sex</option>
                            <option value="female" {{ old('gender', $farmer->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="male" {{ old('gender', $farmer->gender) === 'male' ? 'selected' : '' }}>Male</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row 4: Address (full width) -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">{{ old('address', $farmer->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Row 5: Status -->
                    <div class="md:col-span-2">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="active" {{ old('status', $farmer->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $farmer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Farm Information Section -->
            <div class="px-8 pb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Farm Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Row 1: Farm Size, Farm Type -->
                    <div>
                        <label for="farm_size" class="block text-sm font-semibold text-gray-700 mb-2">Farm Size (hectares) <span class="text-red-500">*</span></label>
                        <input type="text" name="farm_size" id="farm_size" value="{{ old('farm_size', $farmer->farm_size) }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" inputmode="decimal" placeholder="e.g. 2.50">
                        @error('farm_size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="farm_type" class="block text-sm font-semibold text-gray-700 mb-2">Farm Type <span class="text-red-500">*</span></label>
                        <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            Crop
                        </div>
                        <input type="hidden" name="farm_type" value="crop">
                    </div>

                    <!-- Row 2: Document -->
                    <div class="md:col-span-2">
                        <label for="supporting_document" class="block text-sm font-semibold text-gray-700 mb-2">Document <span class="text-red-500">*</span></label>
                        <input type="file" name="supporting_document" id="supporting_document" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        @error('supporting_document')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-2">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB)</p>
                        @if($farmer->supporting_document)
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800">Current document: <a href="{{ asset('storage/' . $farmer->supporting_document) }}" target="_blank" class="font-semibold underline hover:no-underline">View</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 px-8 py-6">
                <a href="{{ route('farmers.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    Update Farmer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/farmers-edit.js')
@endpush