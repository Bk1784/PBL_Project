@extends('dashboard')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Change Password</h2>

    {{-- Error dari validasi --}}
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Error custom dari session (contoh: Password lama salah) --}}
    @if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- Status sukses --}}
    @if (session('status'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('status') }}
    </div>
    @endif

    <form action="{{ route('customer.update_password') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="old_password" class="block">Old Password</label>
            <input type="password" name="old_password" class="border p-2 w-full rounded" required>
        </div>

        <div>
            <label for="new_password" class="block">New Password</label>
            <input type="password" name="new_password" class="border p-2 w-full rounded" required>
        </div>

        <div>
            <label for="new_password_confirmation" class="block">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="border p-2 w-full rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition">
            Save Changes
        </button>
    </form>
</div>
@endsection