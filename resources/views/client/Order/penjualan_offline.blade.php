@extends('client.client_dashboard')

@section('content')
<br><br>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Main Content (Produk) -->
    <div class="flex-1">
        <div class="flex space-x-4 overflow-x-auto p-4 bg-gray-200 rounded-lg">
            <div class="grid grid-cols-3 gap-3 w-full">
                @foreach ($products as $product)
                <div class="p-2 bg-white rounded-lg shadow hover:scale-105 hover:shadow-md transform transition">
                    <div class="flex flex-col items-center justify-between h-full">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-20 object-cover rounded">
                        <h3 class="text-xs font-semibold mt-1 text-center line-clamp-2">{{ $product->name }}</h3>

                        <button 
                            class="mt-2 w-8 h-8 bg-blue-500 text-white flex items-center justify-center rounded-full hover:bg-blue-600 transition add-to-cart" 
                            data-id="{{ $product->id }}" 
                            data-name="{{ $product->name }}" 
                            data-price="{{ $product->price }}" 
                            data-image="{{ $product->image }}">
                            +
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Bar (Keranjang) -->
    <div class="w-full lg:w-80 bg-white p-5 border-t lg:border-l border-gray-300">
        <div class="right-bar-content">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Keranjang Offline</h2>
            
            <div class="mb-6 space-y-3 border-b pb-4">
                <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    Pesanan Anda <span id="cart-count">0</span> Item
                </h3>
                <div id="cart-items">
                    <p class="text-gray-500">Keranjang Anda kosong</p>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="font-semibold text-lg mb-2 flex items-center gap-2">
                    <span>🛒</span> Ringkasan
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Total Items:</span>
                        <span id="total-items">0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Harga:</span>
                        <span class="font-bold" id="total-price">Rp0</span>
                    </div>

                    <form id="checkout-form" action="{{ route('OrderItemClient.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_data" id="cart-data">
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg mt-2 hover:bg-blue-600 transition-colors">
                            Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let cart = {};
    const cartItemsEl = document.getElementById('cart-items');
    const cartCountEl = document.getElementById('cart-count');
    const totalItemsEl = document.getElementById('total-items');
    const totalPriceEl = document.getElementById('total-price');
    const cartDataInput = document.getElementById('cart-data');

    function renderCart() {
        cartItemsEl.innerHTML = '';
        let totalItems = 0, totalPrice = 0;

        Object.values(cart).forEach(item => {
            totalItems += item.qty;
            totalPrice += item.price * item.qty;

            cartItemsEl.innerHTML += `
                <div class="cart-item flex justify-between items-center py-2">
                    <div class="flex items-center gap-4">
                        <img src="${item.image}" alt="${item.name}" width="50" height="50" class="object-cover">
                        <div>
                            <h3 class="font-semibold">${item.name}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded dec" data-id="${item.id}">
                                    -
                                </button>
                                <span class="quantity w-6 text-center">${item.qty}</span>
                                <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded inc" data-id="${item.id}">
                                    +
                                </button>
                                <button class="text-red-500 hover:text-red-700 ml-2 remove-item" data-id="${item.id}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <span class="font-semibold">Rp ${item.price * item.qty}</span>
                </div>
            `;
        });

        if(totalItems === 0) {
            cartItemsEl.innerHTML = '<p class="text-gray-500">Keranjang Anda kosong</p>';
        }

        cartCountEl.textContent = totalItems;
        totalItemsEl.textContent = totalItems;
        totalPriceEl.textContent = 'Rp' + totalPrice.toLocaleString();
        cartDataInput.value = JSON.stringify(cart);
    }

    document.addEventListener('click', function(e) {
        if(e.target.classList.contains('add-to-cart')) {
            const id = e.target.dataset.id;
            if(!cart[id]) {
                cart[id] = {
                    id: id,
                    name: e.target.dataset.name,
                    price: parseFloat(e.target.dataset.price),
                    image: e.target.dataset.image,
                    qty: 1
                };
            } else {
                cart[id].qty++;
            }
            renderCart();
        }

        if(e.target.classList.contains('inc')) {
            cart[e.target.dataset.id].qty++;
            renderCart();
        }

        if(e.target.classList.contains('dec')) {
            const id = e.target.dataset.id;
            cart[id].qty--;
            if(cart[id].qty <= 0) delete cart[id];
            renderCart();
        }

        // hapus item (klik button + SVG di dalamnya)
        if (e.target.closest('.remove-item')) {
            const id = e.target.closest('.remove-item').dataset.id;
            delete cart[id];
            renderCart();
        }
    });

    // Konfirmasi Checkout
    document.getElementById('checkout-form').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Checkout',
            text: "Apakah Anda yakin ingin melanjutkan pembelian?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Checkout'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // SweetAlert sukses / error dari session
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endsection
