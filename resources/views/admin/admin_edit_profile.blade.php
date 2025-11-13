@extends('admin.admin_dashboard')

@section('content')

            
        <!-- Content -->
        <div class=" p-6 w-full">
            <div class="container mx-auto">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Profil Admin</h3>

                    <form id="edit-profile-form" action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <img src="{{ $admin->photo ? asset($admin->photo) : asset('upload/no_image.jpg') }}"
                                    alt="Profile Picture"
                                    class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300 object-cover">
                                <input type="file" name="photo" class="mt-4 block w-full text-sm text-gray-600 border border-gray-300 rounded p-2">
                            </div>
                            <div class="col-span-2">
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Name</label>
                                    <input type="text" name="name" class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300" value="{{ $admin->name }}" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Email</label>
                                    <input type="email" name="email" class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300" value="{{ $admin->email }}" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Contact</label>
                                    <input type="text" name="phone" class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300" value="{{ $admin->phone }}">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Address</label>
                                    <input type="text" name="address" class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300" value="{{ $admin->address }}">
                                </div>
                                <!-- Tombol Simpan -->
                                <button type="button" id="save-button" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>

    <!-- Script Konfirmasi SweetAlert -->
    <script>
        function previewImage(event) {
            const image = document.getElementById('profilePreview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    image.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        };
        document.getElementById('save-button').addEventListener('click', function() {
            Swal.fire({
                title: 'Konfirmasi Simpan Perubahan',
                text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('edit-profile-form').submit();
                }
            });
        });
    </script>
@endsection
