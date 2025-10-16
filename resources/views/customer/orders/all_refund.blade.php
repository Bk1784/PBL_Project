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
                    <th class="p-3 text-left">Detail Refund</th>
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
    onclick="showRefundReason('{{ $refund->refund_reason }}', '{{ asset('storage/' . $refund->refund_image) }}', '{{ $refund->refund_qty }}', {{ $refund->id }}, '{{ number_format($refund->orderItem->price ?? 0, 0, ',', '.') }}', '{{ number_format(($refund->refund_qty * ($refund->orderItem->price ?? 0)), 0, ',', '.') }}')">
    Lihat Detail
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
    function showRefundReason(reason, imageUrl, qty, refundId, unitPrice, refundAmount) {
        let imageHtml = '';
        if (imageUrl && imageUrl !== 'null') {
            imageHtml = `<div style="text-align: center; margin-top: 15px;">
                <img src="${imageUrl}" alt="Bukti Refund" style="max-width: 100%; max-height: 300px; border: 2px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"/>
            </div>`;
        }

        Swal.fire({
            title: '<span style="color: #4F46E5; font-weight: bold;">Detail Refund</span>',
            html: `
                <div style="text-align: left; line-height: 1.6;">
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #4F46E5;">
                        <strong style="color: #4F46E5;">Jumlah Produk yang Dikembalikan:</strong><br>
                        <span style="font-size: 16px; color: #333;">${qty} item</span>
                    </div>
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #4F46E5;">
                        <strong style="color: #4F46E5;">Harga Satuan:</strong><br>
                        <span style="font-size: 16px; color: #333; font-weight: bold;">Rp ${unitPrice}</span>
                    </div>
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #4F46E5;">
                        <strong style="color: #4F46E5;">Jumlah Refund:</strong><br>
                        <span style="font-size: 16px; color: #333; font-weight: bold;">Rp ${refundAmount}</span>
                    </div>
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #4F46E5;">
                        <strong style="color: #4F46E5;">Alasan Refund:</strong><br>
                        <span style="color: #333;">${reason}</span>
                    </div>
                    ${imageHtml}
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="downloadRefundInvoice(${refundId})" style="background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <i class="fas fa-file-invoice" style="margin-right: 8px;"></i>Download Invoice
                        </button>
                    </div>
                </div>
            `,
            width: 650,
            showCloseButton: true,
            showConfirmButton: false,
            focusConfirm: false,
            customClass: {
                popup: 'swal-custom-popup'
            }
        });
    }

    function downloadRefundInvoice(refundId) {
        window.open(`/orders/refund/invoice/${refundId}`, '_blank');
    }
</script>


@endsection
