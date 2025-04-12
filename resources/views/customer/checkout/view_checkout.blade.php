@extends('dashboard') 
@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="mt-6 space-y-6">

    <!-- Shipping and Payment -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Shipping Information -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-300">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>

            @php
                $id = Auth::user()->id;
                $profileData = App\Models\User::find($id);          
                $total = 0;
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
                    <select id="courier" class="w-full px-4 py-2 border border-gray-300 rounded-md" name="courier">
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
                $total = 0;
                if(session('cart')) {
                    foreach(session('cart') as $id => $details) {
                        $total += $details['price'] * $details['qty'];
                    }
                }

                $ongkir = 15000;
                $grandTotal = $total + $ongkir;
            @endphp

            <form action="{{ route('cash_order') }}" method="post">
                @csrf

                {{-- Data pengguna --}}
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                <input type="hidden" name="phone" value="{{ Auth::user()->phone }}">
                <input type="hidden" name="address" value="{{ Auth::user()->address }}">

                {{-- Data pembayaran --}}
                <input type="hidden" id="input_total" name="total" value="{{ $total }}">
                <input type="hidden" id="input_shipping_cost" name="shipping_cost" value="{{ $ongkir }}">
                <input type="hidden" id="input_grand_total" name="grand_total" value="{{ $grandTotal }}">
                <input type="hidden" id="input_courier" name="courier" value="jne">
                <input type="hidden" id="input_payment_method" name="payment_method" value="Transfer Bank">

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Harga</span>
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
                            <input type="radio" id="transfer" name="payment" value="Transfer Bank" checked class="h-4 w-4 text-gray-600 payment-option">
                            <label for="transfer" class="ml-2 block text-sm text-gray-700">Transfer Bank</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="ewallet" name="payment" value="E-Wallet" class="h-4 w-4 text-gray-600 payment-option">
                            <label for="ewallet" class="ml-2 block text-sm text-gray-700">E-Wallet</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="cod" name="payment" value="COD" class="h-4 w-4 text-gray-600 payment-option">
                            <label for="cod" class="ml-2 block text-sm text-gray-700">COD (Bayar di Tempat)</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="mt-6 w-full bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
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

        // Update input hidden
        $('#input_shipping_cost').val(shipping);
        $('#input_grand_total').val(grandTotal);
        $('#input_courier').val(courier);
    });

    $('input[name="payment"]').on('change', function () {
        let method = $(this).val();
        $('#input_payment_method').val(method);
    });
});
</script>

@endsection
