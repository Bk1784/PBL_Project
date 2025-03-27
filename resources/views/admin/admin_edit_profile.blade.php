<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white p-5 fixed h-full shadow-md">
            <h2 class="text-xl font-bold mb-5">Galaxy Store</h2>
            <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-home mr-2"></i>Dashboard
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-wallet mr-2"></i>Pembayaran
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-box mr-2"></i>Produk
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-paint-brush mr-2"></i>Dekorasi
            </a>
            <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-blue-100 rounded mb-2">
                <i class="fa fa-cog mr-2"></i>Pengaturan
            </a>
            <a href="{{ route('admin.logout') }}" class="block py-2 px-4 text-red-500 hover:bg-red-100 rounded">
                <i class="fa fa-sign-out-alt mr-2"></i>Logout
            </a>
        </div>

        <!-- Content -->
        <div class="ml-64 p-6 w-full">
            <div class="container mx-auto">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Profil Admin</h3>

                    @if (session('success'))
                        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="edit-profile-form" action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $admin->photo) }}" alt="Profile Picture" class="w-36 h-36 rounded-full mx-auto border-2 border-gray-300">
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
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Bio</label>
                                    <textarea name="bio" class="block w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-300" rows="3">{{ $admin->bio }}</textarea>
                                </div>
                                <!-- Tambahkan ID pada tombol -->
                                <button type="button" id="save-button" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan JavaScript -->
    <script>
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
</body>
</html>