@php
    $id = Auth::guard('client')->id();
    $client = App\Models\Client::find($id);
    $status = $client->status;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galaxy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#DBE2EF] font-sans antialiased">

    <!-- Layout Utama -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg p-5 border-r border-gray-300">
       
            <h2 class="text-base font-bold mb-6 tracking-wide text-gray-800 text-center">
                @php
                $client = Auth::guard('client')->user();
                $logo = $client && $client->logo ? 'storage/' . $client->logo : 'images/logo.jpg';
                @endphp
                <img src="{{ asset($logo) }}" alt="Logo" class="mx-auto w-32 h-32 object-cover rounded-full">
            </h2>
            <ul class="space-y-4 text-gray-700">
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🏠</span> <a href="{{ route('client.dashboard') }}">Dashboard</a>
                </li>
                @if ($status == '1')
                
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>📦</span> <a href="{{ route('client.Order.penjualan_offline') }}"><span>Produk</span></a> 
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🎨</span> <span>Dekorasi</span>
                </li>
                <li class="flex flex-col gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <div class="flex items-center">
                        <span>🛒</span>
                        <a href="javascript:void(0);" class="has-arrow ml-3 w-full" id="manageOrdersToggle">
                            <span>Manage Orders</span>
                        </a>
                    </div>
                    <ul class="sub-menu hidden mt-2 ml-8 space-y-2 transition-all duration-300 ease-in-out" aria-expanded="false" id="manageOrdersSubmenu">
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('client.pending.orders') }}">Pending Orders</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('client.confirm.orders') }}">Confirm Orders</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('client.processing.orders') }}">Processing Orders</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('client.delivered.orders') }}">Delivered Orders</a>
                        </li>
                    </ul>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>💳</span> <span>Pembayaran</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>📑</span> <a href="{{ route('client.laporan') }}">Laporan</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>⚙️</span> <span>Pengaturan</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>👤</span> <a href="{{ route('client.profile') }}">Profile</a>
                </li>
                <li class="flex items-center gap-3 text-red-500 hover:text-red-400 transition-all cursor-pointer">
                    <span>🚪</span> <a href="{{ route('client.logout') }}">Log Out</a>
                </li>

                @else
                
                @endif
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 max-w-6xl mx-auto mt-10 p-6">
        
            <!-- Header -->
            <div class="bg-white p-4 flex justify-between items-center rounded-lg shadow-md border border-gray-300">
            @if ($status == '1')
                <h1 class="text-3xl font-extrabold tracking-wide text-gray-800">GALAXY STORE</h1>
                @else
                <h1 class="text-3xl font-extrabold tracking-wide text-gray-800">Toko Belum Aktif</h1>
            @endif
                        
                <div class="flex items-center gap-3">
                    <input type="text" placeholder="🔍 Pencarian Produk"
                        class="px-4 py-2 w-72 rounded-full bg-white text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-all duration-300 shadow-sm">

                    <!-- Foto Profil -->
                    <div class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden">
                        <a href="{{ route('client.profile') }}" class="block">
                            @php
                            $client = Auth::guard('client')->user();
                            $photo = $client && $client->photo ? 'storage/' . $client->photo :
                            'profile_photos/default.jpg';
                            @endphp
                            <img src="{{ asset($photo) }}" alt="Profile" class="w-full h-full object-cover">
                        </a>

                    </div>
                </div>
            </div>

            <!-- Konten yang akan berubah -->
            @yield('content')

            <!-- Footer -->
            <div
                class="bg-white text-gray-800 p-6 flex justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300">
                <div>
                    <h2 class="font-semibold text-lg">📍 Alamat</h2>
                    <p class="text-gray-700">Galaxy Store, Srono</p>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">📢 Ikuti Kami</h2>
                    <p class="text-blue-500">Instagram</p>
                    <p class="text-orange-500">Shopee</p>
                </div>
                <div>
                    <h2 class="font-semibold text-lg">📞 Kontak Kami</h2>
                    <p class="text-gray-700">0812-3456-7890</p>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Toggle submenu visibility for Pesanan menu
    document.getElementById('manageOrdersToggle').addEventListener('click', function() {
        const submenu = document.getElementById('manageOrdersSubmenu');
        submenu.classList.toggle('hidden');
    });
    </script>
</body>

</html>
