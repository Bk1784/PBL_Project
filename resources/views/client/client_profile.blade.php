@extends('client.client_dashboard')

@section('content')

<div class="bg-white text-gray-800 p-6 flex justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300">
    <div class="flex items-center gap-6">
        <!-- Foto Profil -->
        <div class="w-1/4 flex justify-center">
            <img src="{{ asset('storage/' . $client->photo) }}" alt="Profile Picture"
                class="rounded-full w-40 h-40 object-cover shadow-md">
        </div>


        <!-- Informasi Client (Grid) -->
        <div class="w-2/4 grid grid-cols-2 gap-4">
            <h2 class="col-span-2 text-2xl font-bold text-center bg-gray-200 p-3 rounded-md">
                Profile
            </h2>
            <div class="border p-3 rounded-md">
                <strong>Username:</strong> {{ $client->name }}
            </div>
            <div class="border p-3 rounded-md">
                <strong>Email:</strong> {{ $client->email }}
            </div>
            <div class="border p-3 rounded-md">
                <strong>Contact:</strong> {{ $client->phone }}
            </div>
            <div class="border p-3 rounded-md">
                <strong>Address:</strong> {{ $client->address }}
            </div>
            <div class="border p-3 rounded-md col-span-2">
                <strong>Bio:</strong> {{ $client->bio }}
            </div>
            <div class="border p-3 rounded-md col-span-2 text-center">
                <strong>Status:</strong> {{ $client->role }}
            </div>
        </div>


        <!-- Tombol Edit -->
        <div class="w-1/4 flex justify-end ml-10">
            <a href="{{ route('client.edit.profile') }}"
                class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition text-center whitespace-nowrap w-full">
                EDIT
            </a>
        </div>

        <!-- Tombol Change Password -->
        <div class="w-1/4 flex justify-end">
            <a href="{{ route('client.change.password') }}"
                class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition text-center whitespace-nowrap">
                CHANGE PASSWORD
            </a>
        </div>
    </div>
</div>


@endsection