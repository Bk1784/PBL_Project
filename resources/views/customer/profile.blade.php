@extends('dashboard')
 
@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@php
    $id = Auth::user()->id;
    $profileData = App\Models\User::find($id);
@endphp

<!-- Script untuk menampilkan Toastr -->
<script>
    @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type){
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
    @endif
</script>
 
<div class="bg-white text-gray-800 p-6 flex justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300">
    <div class="flex items-center gap-6">
        <!-- Foto Profil -->
        <div class="w-1/4 flex justify-center">
            <img src="{{ (!empty($profileData->photo)) ? url('upload/user_images/'.$profileData->photo) : url('upload/no_image.jpg') }}" 
                 alt="Profile Picture"
                 class="rounded-full w-40 h-40 object-cover shadow-md">
        </div>

        <!-- Informasi Client (Grid) -->
        <div class="w-2/4 grid grid-cols-2 gap-4">
            <h2 class="col-span-2 text-2xl font-bold text-center bg-gray-200 p-3 rounded-md">
                Profile
            </h2>
            <div class="border p-3 rounded-md">
                <strong>Username:</strong> {{ $profileData->name }}
            </div>
            <div class="border p-3 rounded-md">
                <strong>Email:</strong>  {{ $profileData->email }}
            </div>
            <div class="border p-3 rounded-md">
                <strong>Contact:</strong> {{ $profileData->phone }}
            </div>
            <div class="border p-3 rounded-md">
                <strong>Address:</strong> {{ $profileData->address }}
            </div>
        </div>

        <!-- Tombol Edit -->
        <div class="w-1/4 flex justify-end ml-10">
            <a href="{{ route('customer.edit_profile') }}"
                class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition text-center whitespace-nowrap w-full">
                EDIT
            </a>
        </div>

        <!-- Tombol Change Password -->
        <div class="w-1/4 flex justify-end">
            <a href=""
                class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition text-center whitespace-nowrap">
                CHANGE PASSWORD
            </a>
        </div>
    </div>
</div>

@endsection