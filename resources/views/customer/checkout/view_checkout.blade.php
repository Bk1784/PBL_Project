@extends('dashboard')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<div class="mt-6 space-y-6">
    <!-- Cart Items -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300" id="cart-container">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Keranjang Belanja</h2>
        <div class="divide-y divide-gray-200">
            <p class="mb-4 text-black"><span class="cart-count">{{ count((array) session('cart')) }}</span> Items</p>

            @php $total = 0 @endphp
            @if (session('cart'))
                @foreach (session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['qty'] @endphp

                    <div class="py-4 flex justify-between items-center" data-item-id="{{ $id }}">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $details['image'] }}" alt="Product" class="w-16 h-16 rounded-md object-cover border border-gray-200">
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $details['name'] }}</h3>
                                <p class="text-sm text-gray-500">Rp{{ number_format($details['price'], 0, ',', '.') }} /item</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center gap-2 mt-1">
                                <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded dec" data-id="{{ $id }}">-</button>
                                <span class="quantity w-6 text-center" data-id="{{ $id }}">{{ $details['qty'] }}</span>
                                <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded inc" data-id="{{ $id }}">+</button>
                                <button class="text-red-500 hover:text-red-700 ml-2 remove-item" data-id="{{ $id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <p class="font-medium w-24 text-right item-total" data-price="{{ $details['price'] }}">
                                Rp{{ number_format($details['price'] * $details['qty'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Shipping and Payment -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Shipping Info -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>

            @php
                $profileData = App\Models\User::find(Auth::id());
            @endphp

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Nama Penerima</label>
                    <input type="text" class="w-full px-4 py-2 border rounded" value="{{ $profileData->name }}" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium">Nomor Telepon</label>
                    <input type="tel" class="w-full px-4 py-2 border rounded" value="{{ $profileData->phone }}" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium">Alamat Lengkap</label>
                    <textarea class="w-full px-4 py-2 border rounded" rows="3" readonly>{{ $profileData->address }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium">Kurir Pengiriman</label>
                    <select id="courier" name="courier_selected" class="w-full px-4 py-2 border rounded">
                        <option value="jne" selected>JNE Reguler</option>
                        <option value="jnt">J&T Express</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="pos">POS Indonesia</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pembayaran</h2>

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Ongkos Kirim</span>
                    <span class="shipping-cost">Rp0</span>
                </div>
                <div class="border-t pt-3 mt-3 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span class="grand-total">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium mb-2">Metode Pembayaran</label>
                <div class="space-y-2">
                    <div><input type="radio" id="cod" value="COD" name="payment"> COD</div>
                    <div><input type="radio" id="midtrans" value="Midtrans" name="payment"> Midtrans</div>
                </div>
            </div>

            <form id="order-form" action="{{ route('customer.orders.cash_order') }}" method="POST">
                @csrf
                <input type="hidden" name="courier_selected" id="courier_selected">
                <input type="hidden" name="payment_selected" id="payment_selected">
                <button type="submit" id="submit-btn" class="mt-6 w-full bg-gray-400 text-white py-3 rounded cursor-not-allowed" disabled>
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let total = {{ $total }};
    const shippingCosts = {
        'jne': 2500,
        'jnt': 5000,
        'sicepat': 2000,
        'pos': 7000
    };

    function updateCosts() {
        let courier = $('#courier').val();
        let shippingFee = shippingCosts[courier] || 0;
        let grandTotal = total + shippingFee;

        $('.shipping-cost').text('Rp' + shippingFee.toLocaleString('id-ID'));
        $('.grand-total').text('Rp' + grandTotal.toLocaleString('id-ID'));
        $('#courier_selected').val(courier); // ✅ penting: masukkan value, bukan text
    }

    updateCosts(); // update saat awal

    $('#courier').change(function() {
        updateCosts();
    });

    $('input[name="payment"]').change(function() {
        let selected = $(this).val();
        $('#payment_selected').val(selected);

        $('#submit-btn').prop('disabled', false)
            .removeClass('bg-gray-400 cursor-not-allowed')
            .addClass('bg-gray-800 hover:bg-gray-700 cursor-pointer');
    });

    $('#order-form').submit(function(e) {
        e.preventDefault();
        let payment = $('#payment_selected').val();

        if (payment === 'Midtrans') {
            $.ajax({
                url: '{{ route("customer.orders.get_midtrans_token") }}',
                type: 'POST',
                data: {
                    courier_selected: $('#courier_selected').val(), // ✅ sudah benar
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.snapToken) {
                        snap.pay(response.snapToken, {
                            onSuccess: function(result) {
                                window.location.href = '/checkout/thanks';
                            },
                            onPending: function(result) {
                                alert('Menunggu pembayaran...');
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal!');
                            }
                        });
                    }
                },
                error: function() {
                    alert('Gagal mendapatkan token Midtrans.');
                }
            });
        } else {
            this.submit(); // COD
        }
    });
});
</script>

@endsection
