@extends('dashboard')

@section('content')

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- All Orders Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Semua Pesanan</h3>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Sl</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Jumlah</th>
                    <th class="p-3 text-left">Pembayaran</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $order)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b border-gray-200">{{ $key + 1 }}</td>
                    <td class="p-3 border-b border-gray-200">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}
                    </td>
                    <td class="p-3 border-b border-gray-200">{{ $order->invoice_no }}</td>
                    <td class="p-3 border-b border-gray-200">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border-b border-gray-200">{{ $order->payment_method }}</td>
                    <td class="p-3 border-b border-gray-200">
                        <span class="bg-green-500 text-white py-1 px-3 rounded-full text-sm">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="p-3 border-b border-gray-200 space-x-1">
                        <!-- Tombol Refund hanya untuk status completed -->
                        @if($order->status === 'completed')
                            <a href="javascript:void(0);" 
                               class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded refund-btn" 
                               data-order-id="{{ $order->id }}"
                               title="Refund">
                                <i class="fas fa-undo"></i>
                            </a>
                        @else
                            <!-- Tombol View -->
                            <!-- <a href="#"
                               class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded" 
                               title="View">
                                <i class="fas fa-eye"></i>
                            </a> -->
                        @endif

                        <!-- Tombol Cancel jika pending/confirmed -->
                        @if(in_array($order->status, ['pending', 'confirmed']))
                            <!-- <a href="#"
                               class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded" 
                               title="Cancel">
                                <i class="fas fa-times"></i>
                            </a> -->
                        @endif

                        <!-- Tombol Invoice -->
                        <a href="{{ route('customer.orders.invoice', $order->id) }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white py-1 px-3 rounded" 
                           title="Invoice">
                            <i class="fas fa-file-invoice"></i>
                        </a>

                        <!-- Tombol Rating jika status completed -->
                        @if($order->status === 'completed')
                            @php
                                $sudahRating = \DB::table('order_ratings')
                                    ->where('order_id', $order->id)
                                    ->where('user_id', auth()->id())
                                    ->exists();
                            @endphp

                            @if($sudahRating)
                                <button 
                                    onclick="Swal.fire({
                                        icon: 'info',
                                        title: 'Sudah Rating',
                                        text: 'Anda sudah memberikan rating untuk pesanan ini.'
                                    })"
                                    class="bg-green-500 text-white py-1 px-3 rounded">
                                    <i class="fas fa-check"></i> Sudah Rating
                                </button>
                            @else
                                <a href="{{ route('customer.rating.rate', $order->id) }}"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded" 
                                   title="Beri Rating">
                                    <i class="fas fa-star"></i> Rating
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Tombol Link ke halaman pesanan refund -->
        <div class="mt-4">
            <a href="{{ route('customer.orders.all_refund') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded">
                Pesanan Refund
            </a>
        </div>

<!-- SweetAlert untuk input alasan refund (perbaikan) -->
<script>
document.querySelectorAll('.refund-btn').forEach(button => {
    button.addEventListener('click', () => {
        const orderId = button.getAttribute('data-order-id');

        Swal.fire({
            title: 'Alasan Refund',
            input: 'textarea',
            inputPlaceholder: 'Masukkan alasan refund...',
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Alasan refund harus diisi');
                }
                return reason;
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Gunakan path yang sesuai dengan route: /orders/{id}/refund
            const url = "{{ url('/orders') }}/" + orderId + "/refund";

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',            // minta JSON agar Laravel merespon JSON pada error validasi
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin', // pastikan cookie/session dikirim
                body: JSON.stringify({ refund_reason: result.value })
            })
            .then(async response => {
                const text = await response.text(); // ambil teks dulu
                // coba parse JSON, tapi jika bukan JSON tampilkan teks aslinya
                let data = null;
                try {
                    data = text ? JSON.parse(text) : null;
                } catch (e) {
                    // bukan JSON
                }

                if (!response.ok) {
                    // berikan pesan error yang lebih berguna
                    const msg = (data && data.message) ? data.message : (text || `HTTP ${response.status}`);
                    throw new Error(msg);
                }

                return data;
            })
            .then(data => {
                if (data && data.success) {
                    Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                } else {
                    // jika API mengirim sukses=false
                    Swal.fire('Gagal', (data && data.message) ? data.message : 'Terjadi kesalahan', 'error');
                }
            })
            .catch(err => {
                console.error('Refund error:', err);
                Swal.fire('Gagal', 'Terjadi kesalahan server: ' + err.message, 'error');
            });
        });
    });
});
</script>


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

@endsection
