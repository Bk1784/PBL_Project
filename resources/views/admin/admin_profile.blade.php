@extends('admin.admin_dashboard')

@section('content')

<div class="p-6 w-full">
    <div class="container mx-auto">
        <div
            class="p-8 bg-white rounded-lg shadow-md border border-gray-300 flex flex-col md:flex-row items-center gap-8">
            <!-- Profile Picture -->
            <div>
                <img src="{{ asset( $admin->photo) }}"
                    alt="Profile Picture" class="rounded-full w-32 h-32 object-cover shadow-md border">
            </div>

            <!-- Admin Info dalam Card -->
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <p class="text-gray-700 font-semibold">Nama</p>
                    <h2 class="text-lg font-bold">{{ $admin->name }}</h2>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <p class="text-gray-700 font-semibold">Email</p>
                    <p>{{ $admin->email }}</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <p class="text-gray-700 font-semibold">Contact</p>
                    <p>{{ $admin->phone ?? '-' }}</p>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md border">
                    <p class="text-gray-700 font-semibold">Address</p>
                    <p>{{ $admin->address ?? '-' }}</p>
                </div>
            </div>

            <!-- Edit Button -->
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.edit.profile') }}"
                    class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 transition text-center">
                    EDIT
                </a>
                <a href="{{ route('admin.change.password') }}"
                    class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700 transition text-center">
                    CHANGE PASSWORD
                </a>
            </div>
        </div>
    </div>
</div>

@endsection