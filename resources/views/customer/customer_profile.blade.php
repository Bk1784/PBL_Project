@extends('customer.customer_dashboard')

@section('content')

<div
    class="bg-white text-gray-800 p-6 flex flex-col md:flex-row justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300 gap-6">
    <div class="flex items-center gap-6 w-full">
        <!-- Foto Profil -->
        <div class="w-full md:w-1/4 flex justify-center">
            <img src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('profile_photos/default.jpg') }}"
                alt="Profile Picture" class="rounded-full w-40 h-40 object-cover shadow-md border border-gray-300">
        </div>

        <!-- Informasi Customer -->
        <div class="w-full md:w-2/4 grid grid-cols-2 gap-4">
            <h2 class="col-span-2 text-2xl font-bold text-center bg-gray-200 p-3 rounded-md">
                Profile
            </h2>

            <div class="border p-3 rounded-md">
                <strong>Nama:</strong> {{ $customer->nama }}
            </div>

            <div class="border p-3 rounded-md">
                <strong>Username:</strong> {{ $customer->username }}
            </div>

            <div class="border p-3 rounded-md">
                <strong>Email:</strong> {{ $customer->email }}
            </div>

            <div class="border p-3 rounded-md">
                <strong>Kontak:</strong> {{ $customer->kontak }}
            </div>

            <div class="border p-3 rounded-md">
                <strong>Alamat:</strong> {{ $customer->alamat }}
            </div>

            <div class="border p-3 rounded-md">
                <strong>Jenis Kelamin:</strong> {{ $customer->jenis_kelamin ?? '-' }}
            </div>

            <div class="col-span-2 border p-3 rounded-md col-span-2">
                <strong>Bio:</strong> {{ $customer->bio ?? '-' }}
            </div>
        </div>

        <!-- Aksi -->
        <div class="w-full md:w-1/4 flex flex-col gap-4 justify-start items-center">
            <a href="{{ route('customer.profile.edit') }}"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition w-full text-center">
                Edit Profile
            </a>

            <a href="{{ route('customer.change.password') }}"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition w-full text-center">
                Change Password
            </a>
        </div>
    </div>
</div>

@endsection