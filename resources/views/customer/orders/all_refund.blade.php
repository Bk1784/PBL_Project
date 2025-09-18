@extends('dashboard')

@section('content')

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- All Refunds Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Semua Pesanan Refund</h3>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Sl</th>
                    <th class="p-3 text-left">Tanggal Refund</th>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Jumlah</th>
                    <th class="p-3 text-left">Alasan Refund</th>
                    <th class="p-3 text-left">Status Refund</th>
                    <th class="p-3 text-left">Alasan Penolakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($refunds as $key => $refund)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b border-gray-200">{{ $key + 1 }}</td>
                    <td class="p-3 border-b border-gray-200">
                        {{ \Carbon\Carbon::parse($refund->created_at)->format('d F Y') }}
                    </td>
                    <td class="p-3 border-b border-gray-200">{{ $refund->order->invoice_no }}</td>
                    <td class="p-3 border-b border-gray-200">
                        Rp {{ number_format($refund->order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border-b border-gray-200">
    <button 
        class="bg-purple-500 text-white py-1 px-3 rounded text-sm hover:bg-purple-600"
        onclick="showRefundReason('{{ $refund->refund_reason }}', '{{ asset('storage/' . $refund->refund_image) }}')">
        Lihat Alasan
    </button>
</td>

                    <td class="p-3 border-b border-gray-200">
                        @if($refund->status === 'pending')
                            <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">
                                Menunggu
                            </span>
                        @elseif($refund->status === 'rejected')
                            <span class="bg-red-500 text-white py-1 px-3 rounded-full text-sm">
                                Ditolak
                            </span>
                        @elseif($refund->status === 'accepted')
                            <button
                                class="bg-blue-500 text-white py-1 px-3 rounded-full text-sm focus:outline-none"
                                onclick="showAcceptedAlert()"
                                type="button"
                            >
                                Diterima
                            </button>
                        @elseif($refund->status === 'completed')
                            <span class="bg-green-500 text-white py-1 px-3 rounded-full text-sm">
                                Selesai
                            </span>
                        @endif
                    </td>
                    <td class="p-3 border-b border-gray-200">
                        {{ $refund->reject_reason ?: '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

        <div class="mt-4">
            <a href="{{ route('customer.orders.all_orders') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded">
                Semua Pesanan
            </a>
        </div>

<!-- SweetAlert untuk pesan sukses/error -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        })
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            showConfirmButton: true
        })
    </script>
@endif

<!-- Tambahkan script SweetAlert untuk tombol Diterima -->
<script>
    function showAcceptedAlert() {
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: 'mohon kembalikan produk ke toko untuk mendapatkan pengembalian dana',
            showConfirmButton: true
        });
    }
</script>

<script>
    function showRefundReason(reason, imageUrl) {
        Swal.fire({
            title: 'Alasan Refund',
            html: `
                <p style="margin-bottom: 15px;">${reason}</p>
                <img src="${imageUrl}" alt="Bukti Refund" style="max-width: 100%; height: auto; border-radius: 8px;"/>
            `,
            width: 600,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#6B46C1'
        });
    }
</script>


@endsection
