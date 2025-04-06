@extends('customer.customer_dashboard')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Change Password</h2>

    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('customer.update.password') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Old Password --}}
        <div>
            <label for="old_password" class="block">Old Password</label>
            <input type="password" name="old_password" class="border p-2 w-full rounded" required>
            @error('old_password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="new_password" class="block">New Password</label>
            <input type="password" name="new_password" class="border p-2 w-full rounded" required>
            @error('new_password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm New Password --}}
        <div>
            <label for="new_password_confirmation" class="block">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="border p-2 w-full rounded" required>
            @error('new_password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            Save Changes
        </button>
    </form>
</div>
@endsection