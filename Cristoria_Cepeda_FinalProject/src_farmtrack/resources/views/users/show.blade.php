@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
        <a href="{{ route('users.index') }}" class="text-gray-900 hover:underline   ">
            ← Back to Users
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
        </div>
        
        <div class="px-6 py-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">ID</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->id }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Name</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Email</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Role</label>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $user->role->slug === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($user->role->name) }}
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Registered</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->created_at->format('F d, Y h:i A') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                <p class="mt-1 text-lg text-gray-900">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('users.edit', $user->id) }}" class="px-4 py-2 text-gray-900 rounded-lg hover:underline">
                Edit User
            </a>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-gray-900 rounded-lg hover:underline">
                    Delete User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
