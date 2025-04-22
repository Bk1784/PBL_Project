<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Galaxy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <style>
    .main-content-container {
        display: flex;
        width: 100%;
        flex-direction: column;
    }

    @media (min-width: 1024px) {
        .main-content-container {
            flex-direction: row;
        }
    }

    .content-area {
        flex: 1;
        min-width: 0;
        order: 1;
    }

    .right-bar-container {
        width: 100%;
        order: 2;
    }

    @media (min-width: 1024px) {
        .right-bar-container {
            width: 320px;
            order: 1;
        }
    }

    .sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        bottom: 0;
        z-index: 50;
        transition: left 0.3s ease;
    }

    .sidebar.active {
        left: 0;
    }

    @media (min-width: 1024px) {
        .sidebar {
            position: relative;
            left: 0;
        }
    }

    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }

    .overlay.active {
        display: block;
    }

    .mobile-menu-button {
        display: block;
    }

    @media (min-width: 1024px) {
        .mobile-menu-button {
            display: none;
        }
    }

    @media (max-width: 1023px) {
        .right-bar-container {
            position: sticky;
            bottom: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            z-index: 30;
        }

        .right-bar-content {
            max-height: 200px;
            overflow-y: auto;
        }
    }

    .cart-item {
        transition: all 0.3s ease;
    }

    .cart-item-removed {
        opacity: 0;
        transform: translateX(-100%);
        height: 0;
        padding: 0;
        margin: 0;
        border: none;
    }
    </style>
</head>

<body class="bg-[#DBE2EF] font-sans antialiased">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-button fixed top-4 left-4 z-30 bg-white p-2 rounded-lg shadow-lg lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Overlay untuk mobile menu -->
    <div class="overlay"></div>

    <!-- Layout Utama -->
    <div class="flex min-h-screen pt-16 lg:pt-0">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white shadow-lg p-5 border-r border-gray-300 flex-shrink-0">
            <div class="flex justify-between items-center mb-6 lg:block">
                <h2 class="text-base font-bold tracking-wide text-gray-800">
                    <img src="{{ asset('images/logo.jpg') }}" width="100" height="100" alt="" class="ml-0 lg:ml-12">
                </h2>
                <button class="close-sidebar lg:hidden text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="space-y-4 text-gray-700">
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🏠</span> <a href="{{ route('atk_dashboard') }}">Dashboard</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>📦</span> <a href="{{ route('atk_dashboard') }}">Produk</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🎨</span> <span>Dekorasi</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>⚙️</span> <span>Pengaturan</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🛒</span> <a href="#">Keranjang</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>💳</span> <a href="#">Checkout</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>👤</span> <a href="{{ route('customer.profile') }}">Profile</a>
                </li>
                <div>
    <a href="{{ route('admin.logout') }}" class="block py-2 px-4 text-red-500 hover:bg-red-100 rounded">
        <i class="fa fa-sign-out-alt mr-2"></i>Logout
    </a>
</div>

        <!-- Konten Utama dan Right Bar Container -->
        <div class="main-content-container w-full">
            <!-- Konten Utama -->
            <div class="content-area p-4 lg:p-6">
                <!-- Header -->
                <div
                    class="bg-white p-4 flex flex-col md:flex-row justify-between items-center gap-4 rounded-lg shadow-md border border-gray-300">
                    <h1
                        class="text-2xl lg:text-3xl font-extrabold tracking-wide text-gray-800 text-center md:text-left">
                        GALAXY STORE</h1>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input type="text" placeholder="🔍 Pencarian Produk"
                            class="px-4 py-2 w-full md:w-72 rounded-full bg-white text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-all duration-300 shadow-sm">
                        <div class="w-10 h-10 bg-gray-400 rounded-full overflow-hidden shrink-0">
                            <a href="{{ route('customer.profile') }}" class="block">
                                @php
                                $user = Auth::guard('admin')->user();
                                $photo = $user && $admin->photo ? 'storage/' . $user->photo :
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
                    class="bg-white text-gray-800 p-6 flex flex-col md:flex-row justify-between text-sm rounded-lg shadow-md mt-6 border border-gray-300 gap-6">
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

            <!-- RIGHT BAR - Informasi -->
            <div class="right-bar-container bg-white p-5 border-t lg:border-l border-gray-300">
                <div class="right-bar-content">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">Informasi</h2>

                    <!-- Daftar Produk di Keranjang -->
                    <div class="mb-6 space-y-3 border-b pb-4">
                        <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                            Pesanan Anda <span id="cart-count">{{ count((array) session('cart')) }}</span> Item
                        </h3>

                        <div id="cart-items">
                            @php $total = 0; $totalItems = 0; @endphp
                            @if(session('cart'))
                            @foreach(session('cart') as $id => $details)
                            @php
                            $total += $details['price'] * $details['qty'];
                            $totalItems += $details['qty'];
                            @endphp
                            <div class="cart-item flex justify-between items-center py-2" id="cart-item-{{ $id }}">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" width="50"
                                        height="50" class="object-cover">
                                    <div>
                                        <h3 class="font-semibold">{{ $details['name'] }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button
                                                class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded dec"
                                                data-id="{{ $id }}">
                                                -
                                            </button>
                                            <span class="quantity w-6 text-center">{{ $details['qty'] }}</span>
                                            <button
                                                class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded inc"
                                                data-id="{{ $id }}">
                                                +
                                            </button>
                                            <button class="text-red-500 hover:text-red-700 ml-2 remove-item"
                                                data-id="{{ $id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span class="font-semibold"
                                    id="item-price-{{ $id }}">Rp{{ $details['price'] * $details['qty'] }}</span>
                            </div>
                            @endforeach
                            @else
                            <p class="text-gray-500">Keranjang Anda kosong</p>
                            @endif
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                            <span>🛒</span> Ringkasan Keranjang
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Total Items:</span>
                                <span id="total-items">{{ $totalItems }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Harga:</span>
                                <span class="font-bold" id="total-price">Rp{{ $total }}</span>
                            </div>
                            <button
                                class="w-full bg-blue-500 text-white py-2 rounded-lg mt-2 hover:bg-blue-600 transition-colors">
                                Lihat Keranjang
                            </button>
                        </div>
                    </div>

                    <!-- Recent Activity (Hanya ditampilkan di desktop) -->
                    <div class="hidden lg:block">
                        <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                            <span>🔄</span> Aktivitas Terkini
                        </h3>
                        <ul class="space-y-3">
                            <li class="text-sm flex items-start gap-2">
                                <span>📦</span> Produk "Buku Tulis" telah ditambahkan
                            </li>
                            <li class="text-sm flex items-start gap-2">
                                <span>💬</span> Ulasan baru diterima
                            </li>
                            <li class="text-sm flex items-start gap-2">
                                <span>🛒</span> Pesanan #1234 diproses
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type','info') }}"
    switch (type) {
        case 'info':
            toastr.info(" {{ Session::get('message') }} ");
            break;
        case 'success':
            toastr.success(" {{ Session::get('message') }} ");
            break;
        case 'warning':
            toastr.warning(" {{ Session::get('message') }} ");
            break;
        case 'error':
            toastr.error(" {{ Session::get('message') }} ");
            break;
    }
    @endif

    // Mobile menu toggle
    $(document).ready(function() {
        $('.mobile-menu-button').click(function() {
            $('.sidebar').addClass('active');
            $('.overlay').addClass('active');
        });

        $('.close-sidebar, .overlay').click(function() {
            $('.sidebar').removeClass('active');
            $('.overlay').removeClass('active');
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Increment quantity
        $(document).on('click', '.inc', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var quantityElement = $(this).siblings('.quantity');
            var currentQuantity = parseInt(quantityElement.text());
            var newQuantity = currentQuantity + 1;
            quantityElement.text(newQuantity);
            updateCart(id, newQuantity);
        });

        // Decrement quantity
        $(document).on('click', '.dec', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var quantityElement = $(this).siblings('.quantity');
            var currentQuantity = parseInt(quantityElement.text());
            if (currentQuantity > 1) {
                var newQuantity = currentQuantity - 1;
                quantityElement.text(newQuantity);
                updateCart(id, newQuantity);
            }
        });

        // Remove item
        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                removeItem(id);
            }
        });

        function updateCart(id, quantity) {
            $.ajax({
                url: '{{ route("cart.updateQuantity") }}',
                method: 'POST',
                data: {
                    id: id,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Update price display
                    $('#item-price-' + id).text('Rp' + (response.price * quantity));

                    // Update totals
                    $('#total-items').text(response.totalItems);
                    $('#total-price').text('Rp' + response.grandTotal);
                    $('#cart-count').text(Object.keys(response.cart).length);

                    toastr.success('Keranjang berhasil diperbarui');
                },
                error: function(xhr) {
                    toastr.error('Gagal memperbarui keranjang');
                    location.reload(); // Fallback
                }
            });
        }

        function removeItem(id) {
            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Animate removal
                    $('#cart-item-' + id).addClass('cart-item-removed');

                    // Remove after animation
                    setTimeout(function() {
                        $('#cart-item-' + id).remove();

                        // Update totals
                        $('#total-items').text(response.totalItems);
                        $('#total-price').text('Rp' + response.grandTotal);
                        $('#cart-count').text(Object.keys(response.cart).length);

                        // If cart is empty, show empty cart message
                        if (response.cartCount === 0) {
                            $('#cart-items').html(
                                '<p class="text-gray-500">Keranjang Anda kosong</p>');
                        }

                        toastr.success('Item berhasil dihapus');
                    }, 300);
                },
                error: function(xhr) {
                    toastr.error('Gagal menghapus item');
                    location.reload(); // Fallback
                }
            });
        }
    });
    </script>
</body>

</html>