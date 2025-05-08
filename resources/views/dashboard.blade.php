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
                <li class="relative">
                    <button id="customerOrdersToggle"
                        class="flex items-center gap-3 w-full text-left hover:text-gray-500 transition-all cursor-pointer">
                        <span>📦</span>
                        <span>Pesanan Saya</span>
                        <svg id="customerOrdersArrow" class="ml-auto w-4 h-4 transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <ul id="customerOrdersSubmenu" class="hidden mt-2 ml-6 space-y-1">
                        <li>
                            <a href="{{ route('customer.orders.all_orders') }}"
                                class="block p-2 rounded hover:bg-gray-100 transition-all">Semua</a>
                        </li>
                    </ul>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>⚙</span> <span>Pengaturan</span>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>🛒</span> <a href="#">Keranjang</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>💳</span> <a href="{{ route ('customer.checkout.view_checkout') }}">Checkout</a>
                </li>
                <li class="flex items-center gap-3 hover:text-gray-500 transition-all cursor-pointer">
                    <span>👤</span> <a href="{{ route('customer.profile') }}">Profile</a>
                </li>
                <li class="flex items-center gap-3 text-red-500 hover:text-red-400 transition-all cursor-pointer">
                    <span>🚪</span> <a href="{{ route('customer.logout') }}">Log Out</a>
                </li>
            </ul>
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
                                $user = Auth::guard('customer')->user();
                                $photo = $user && $user->photo ? 'storage/' . $user->photo :
                                'profile_photos/default.jpg';
                                @endphp
                                <img src="{{ asset($photo) }}" alt="Profile" class="w-full h-full object-cover">
                            </a>
                        </div>
                    </div>
                </div>

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


        </div>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js">
        </script>

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
                        console.error(xhr.responseText);
                        location.reload();
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
                                    '<p class="text-gray-500">Keranjang Anda kosong</p>'
                                );
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

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('customerOrdersToggle');
            const submenu = document.getElementById('customerOrdersSubmenu');
            const arrow = document.getElementById('customerOrdersArrow');

            toggleBtn.addEventListener('click', function() {
                submenu.classList.toggle('hidden');
                arrow.classList.toggle('rotate-90');
            });
        });
        </script>
</body>

</html>