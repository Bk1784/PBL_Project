@extends('client.client_dashboard')

@section('content')

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Processing Orders Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Processing Orders</h3>

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
                @if($orders->isEmpty())
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada pesanan diproses.</td>
                </tr>
                @else
                @foreach($orders as $key => $order)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b border-gray-200">{{ $key + 1 }}</td>
                    <td class="p-3 border-b border-gray-200">
                        {{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $order->invoice_no }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $order->total_price }}</td>
                    <td class="p-3 border-b border-gray-200">{{ $order->payment_method ?? '-' }}</td>
                    <td class="p-3 border-b border-gray-200">
                        <span
                            class="bg-purple-500 text-white py-1 px-3 rounded-full text-sm">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="p-3 border-b border-gray-200">
                        <form action="{{ route('client.pesanan.process', $order->id) }}" method="POST" class="process-order-form" data-order-items="{{ $order->orderItems->toJson() }}">
                            @csrf
                            <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-3 rounded">
                                Proses
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.process-order-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const orderItems = JSON.parse(form.getAttribute('data-order-items'));
                let htmlContent = '<div class="text-left">';
                orderItems.forEach(item => {
                    htmlContent += '<div class="flex items-center mb-2"><div><p class="font-semibold">' + item.product.name + '</p><p>Qty: ' + item.qty + ' | Harga: Rp ' + item.price.toLocaleString() + '</p></div></div>';
                });
                htmlContent += '</div>';
                Swal.fire({
                    title: 'Detail Pesanan',
                    html: htmlContent,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#EF4444',
                    confirmButtonText: 'Ya, Proses!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        background: '#ffffff',
        backdrop: `
            rgba(16, 185, 129, 0.1)
            url("/images/checkmark.gif")
            center top
            no-repeat
        `
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false,
        background: '#ffffff'
    });
</script>
@endif

@endsection
