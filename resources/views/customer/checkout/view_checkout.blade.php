@extends('dashboard')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="mt-6 space-y-6">
    <!-- Cart Items -->
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Keranjang Belanja</h2>
        <div class="divide-y divide-gray-200">
            <p class="mb-4 text-black">{{ count((array) session('cart')) }} Items</p>

            @php $total = 0 @endphp
            @if (session('cart'))
                @foreach (session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['qty'] @endphp

                    <div class="py-4 flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $details['image'] }}" alt="Product" class="w-16 h-16 rounded-md object-cover border border-gray-200">
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $details['name'] }}</h3>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                        <div class="flex items-center gap-2 mt-1">
                            <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded dec" data-id="{{ $id }}">
                                -
                            </button>
                            <span class="quantity w-6 text-center">{{ $details['qty'] }}</span>
                            <button class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded inc" data-id="{{ $id }}">
                                +
                            </button>
                            <button class="text-red-500 hover:text-red-700 ml-2 remove-item" data-id="{{ $id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                            <p class="font-medium w-24 text-right">Rp{{ $details['price'] * $details['qty'] }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Shipping and Payment -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Shipping Information -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>

            @php
                $id = Auth::user()->id;
                $profileData = App\Models\User::find($id);          
            @endphp
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ $profileData->name }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="tel" class="w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ $profileData->phone }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-md" rows="3">{{ $profileData->address }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kurir Pengiriman</label>
                    <select id="courier" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <option value="jne">JNE Reguler</option>
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
            
            @php
                $ongkir = 15000;
                $grandTotal = $total + $ongkir;
            @endphp

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium" id="subtotal">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Ongkos Kirim</span>
                    <span class="font-medium shipping-cost">Rp{{ number_format($ongkir, 0, ',', '.') }}</span>
                </div>
                
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total Pembayaran</span>
                        <span class="grand-total">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="radio" id="transfer" name="payment" checked class="h-4 w-4 text-gray-600">
                        <label for="transfer" class="ml-2 block text-sm text-gray-700">Transfer Bank</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="ewallet" name="payment" class="h-4 w-4 text-gray-600">
                        <label for="ewallet" class="ml-2 block text-sm text-gray-700">E-Wallet</label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="cod" name="payment" class="h-4 w-4 text-gray-600">
                        <label for="cod" class="ml-2 block text-sm text-gray-700">COD (Bayar di Tempat)</label>
                    </div>
                </div>
            </div>
            
            <button class="mt-6 w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                Konfirmasi Pembayaran
            </button>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function () {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 500,
        timerProgressBar: true
    });

    function getShippingCost(courier) {
        switch (courier) {
            case 'jne': return 15000;
            case 'jnt': return 17000;
            case 'sicepat': return 13000;
            case 'pos': return 14000;
            default: return 15000;
        }
    }

    $('#courier').on('change', function () {
        let courier = $(this).val();
        let shipping = getShippingCost(courier);
        let subtotal = {{ $total }};
        let grandTotal = subtotal + shipping;

        $('.shipping-cost').text('Rp' + shipping.toLocaleString('id-ID'));
        $('.grand-total').text('Rp' + grandTotal.toLocaleString('id-ID'));
    });

    $(document).on('click', '.inc', function () {
        let id = $(this).data('id');
        let input = $(this).siblings('input');
        let newQty = parseInt(input.val()) + 1;
        updateQuantity(id, newQty);
    });

    $(document).on('click', '.dec', function () {
        let id = $(this).data('id');
        let input = $(this).siblings('input');
        let newQty = parseInt(input.val()) - 1;
        if (newQty >= 1) updateQuantity(id, newQty);
    });

    $(document).on('click', '.remove', function () {
        let id = $(this).data('id');
        removeFromCart(id);
    });

    function updateQuantity(id, quantity) {
        $.post('{{ route("cart.updateQuantity") }}', {
            _token: '{{ csrf_token() }}',
            id: id,
            quantity: quantity
        }, function () {
            Toast.fire({ icon: 'success', title: 'Quantity Updated' })
                .then(() => location.reload());
        });
    }

    function removeFromCart(id) {
        $.post('{{ route("cart.remove") }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function () {
            Toast.fire({ icon: 'success', title: 'Item Removed' })
                .then(() => location.reload());
        });
    }
});
</script>


@endsection