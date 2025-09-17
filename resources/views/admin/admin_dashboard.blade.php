<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Galaxy Store</title>


    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


    <style>
    /* Custom styling for toggle switch */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #10B981;
    }

    .toggle-checkbox:checked+.toggle-label {
        background-color: #10B981;
    }

    /* Sidebar toggle for mobile */
    .sidebar {
        transition: transform 0.3s ease-in-out;
    }

    .sidebar-hidden {
        transform: translateX(-100%);
    }

    .sidebar-visible {
        transform: translateX(0);
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10;
    }

    .overlay-visible {
        display: block;
    }

    @media (min-width: 768px) {
        .overlay {
            display: none !important;
        }
    }
    </style>
</head>

<body class="bg-[#DBE2EF] font-sans antialiased">

    <!-- Mobile menu button -->
    <div class="md:hidden fixed top-4 left-4 z-20">
        <button id="mobileMenuButton" class="p-2 rounded-md bg-white shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Overlay for mobile menu -->
    <div id="overlay" class="overlay"></div>

    <!-- Layout Utama -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar"
            class="sidebar fixed md:relative z-20 w-64 bg-white shadow-lg p-5 border-r border-gray-300 md:translate-x-0 sidebar-hidden md:sidebar-visible">
            <div class="flex justify-between items-center mb-6 md:mb-6">
                <h2 class="text-base font-bold tracking-wide text-gray-800">
                    <img src="{{ asset('images/logo.jpg') }}" width="100" height="100" alt="" class="ml-0 md:ml-12">
                </h2>
                <button id="closeSidebar" class="md:hidden p-1 rounded-full hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="space-y-4 text-gray-700">
                <li
                    class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>🏠</span> <span>Dashboard</span>
                </li>
                <li
                    class="flex flex-col gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <div class="flex items-center">
                        <span>🤝</span>
                        <a href="javascript:void(0);" class="has-arrow ml-3 w-full manageStoreToggle"
                            onclick="toggleManageStore()">
                            <span>Manage Toko</span>
                        </a>
                    </div>
                    <ul class="sub-menu hidden mt-2 ml-8 space-y-2" aria-expanded="false" id="manageStoreSubmenu">
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('pending.toko') }}">Pending Toko</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('approve.toko') }}">Approve Toko</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="flex flex-col gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <div class="flex items-center">
                        <span>📦</span>
                        <a href="javascript:void(0);" class="has-arrow ml-3 w-full" id="manageProductToggle">
                            <span>Manage Product</span>
                        </a>
                    </div>
                    <ul class="sub-menu hidden mt-2 ml-8 space-y-2" aria-expanded="false" id="manageProductSubmenu">
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.all.product') }}">All Produk</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.add.product') }}">Add Product</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="flex flex-col gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <div class="flex items-center">
                        <span>📦</span>
                        <a href="javascript:void(0);" class="has-arrow ml-3 w-full" id="managePurchasesToggle">
                            <span>Purchases Product</span>
                        </a>
                    </div>
                    <ul class="sub-menu hidden mt-2 ml-8 space-y-2" aria-expanded="false" id="managePurchasesSubmenu">
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.backend.purchases.all') }}">All Purchases</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.backend.purchases.add') }}">Add Purchases</a>
                        </li>
                    </ul>
                </li>
                <li
                    class="flex flex-col gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <div class="flex items-center">
                        <span>🛒</span>
                        <a href="javascript:void(0);" class="has-arrow ml-3 w-full" id="manageOrdersToggle">
                            <span>Manage Orders</span>
                        </a>
                    </div>
                    <ul class="sub-menu hidden mt-2 ml-8 space-y-2 transition-all duration-300 ease-in-out"
                        aria-expanded="false" id="manageOrdersSubmenu">
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.pending.orders') }}">Pending Orders</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.confirm.orders') }}">Confirm Orders</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.processing.orders') }}">Processing Orders</a>
                        </li>
                        <li class="p-1 rounded hover:bg-gray-100">
                            <a href="{{ route('admin.delivered.orders') }}">Delivered Orders</a>
                        </li>
                    </ul>
                </li>

                <div class="flex flex-col gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
        <div class="flex items-center">
            <span>📊</span>
            <a href="javascript:void(0);" class="has-arrow ml-3 w-full" id="manageReportToggle">
                <span>Manage Report</span>
            </a>
        </div>
        <!-- Submenu -->
        <div id="reportSubmenu" class="ml-8 hidden flex-col gap-2">
            <a href="{{ route('admin.all.reports') }}" class="hover:text-gray-700 transition">All Reports</a>
        </div>
        <div id="reportSubmenudua" class="ml-8 hidden flex-col gap-2">
            <a href="{{ route('admin.offline.all.reports') }}" class="hover:text-gray-700 transition">Offline Reports</a>
        </div>
    </div>
                <li
                    class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>🎨</span> <span>Dekorasi</span>
                </li>
                <li
                    class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>💳</span> <span>Pembayaran</span>
                </li>
                <li
                    class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>⚙️</span> <span>Pengaturan</span>
                </li>
                <li
                    class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>👤</span> <a href="{{ route('admin.profile') }}" class="w-full">Profile</a>
                </li>
                <li
                    class="flex items-center gap-3 text-red-500 hover:text-red-400 transition-all cursor-pointer p-2 rounded hover:bg-gray-100">
                    <span>🚪</span> <a href="{{ route('admin.logout') }}" class="w-full">Log Out</a>
                </li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="flex-1 max-w-6xl mx-auto mt-10 p-4 md:p-6 md:ml-0">
            <!-- Header -->
            <div
                class="bg-white p-4 flex flex-col md:flex-row justify-between items-center rounded-lg shadow-md border border-gray-300 gap-4 md:gap-0">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-wide text-gray-800">GALAXY STORE</h1>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <input type="text" placeholder="🔍 Pencarian Produk"
                        class="px-4 py-2 w-full md:w-72 rounded-full bg-white text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-all duration-300 shadow-sm">
                    <div class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden shrink-0">
                        <a href="{{ route('admin.profile') }}" class="block">
                            @php
                            $admin = Auth::guard('admin')->user();
                            $photo = $admin && $admin->photo ? 'storage/' . $admin->photo :
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
                class="bg-white text-gray-800 p-6 flex flex-col md:flex-row justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300 gap-6 md:gap-0">
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
    // Toggle submenu
    document.getElementById('manageProductToggle').addEventListener('click', function() {
        const submenu = document.getElementById('manageProductSubmenu');
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
        } else {
            submenu.classList.add('hidden');
        }
    });

    document.getElementById('managePurchasesToggle').addEventListener('click', function() {
        const submenu = document.getElementById('managePurchasesSubmenu');
        if (submenu.classList.contains('hidden')) {
            submenu.classList.remove('hidden');
        } else {
            submenu.classList.add('hidden');
        }
    });

    // Toggle submenu visibility
    document.getElementById('manageOrdersToggle').addEventListener('click', function() {
        const submenu = document.getElementById('manageOrdersSubmenu');
        submenu.classList.toggle('hidden');
    });


    // Mobile menu toggle
    document.getElementById('mobileMenuButton').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('sidebar-hidden');
        document.getElementById('sidebar').classList.add('sidebar-visible');
        document.getElementById('overlay').classList.add('overlay-visible');
    });

    document.getElementById('closeSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('sidebar-visible');
        document.getElementById('sidebar').classList.add('sidebar-hidden');
        document.getElementById('overlay').classList.remove('overlay-visible');
    });

    document.getElementById('overlay').addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('sidebar-visible');
        document.getElementById('sidebar').classList.add('sidebar-hidden');
        document.getElementById('overlay').classList.remove('overlay-visible');
    });

    // Close sidebar when clicking on a link (for mobile)
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                document.getElementById('sidebar').classList.remove('sidebar-visible');
                document.getElementById('sidebar').classList.add('sidebar-hidden');
                document.getElementById('overlay').classList.remove('overlay-visible');
            }
        });
    });
    </script>

    <script>
    function toggleManageStore() {
        const submenu = document.getElementById('manageStoreSubmenu');
        const isExpanded = submenu.getAttribute('aria-expanded') === 'true';

        submenu.classList.toggle('hidden');
        submenu.setAttribute('aria-expanded', !isExpanded);
    }

    // Optional: Close submenu when clicking elsewhere
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.manageStoreToggle') && !event.target.closest('#manageStoreSubmenu')) {
            const submenu = document.getElementById('manageStoreSubmenu');
            submenu.classList.add('hidden');
            submenu.setAttribute('aria-expanded', 'false');
        }
    });
    </script>

        <!-- Tambahkan JavaScript jika ingin membuat toggle submenu -->
    <script>
        document.getElementById("manageReportToggle").addEventListener("click", function () {
            const submenu = document.getElementById("reportSubmenu");
            submenu.classList.toggle("hidden");
        });
        document.getElementById("manageReportToggle").addEventListener("click", function () {
            const submenu = document.getElementById("reportSubmenudua");
            submenu.classList.toggle("hidden");
        });
    </script>
</body>

</html>