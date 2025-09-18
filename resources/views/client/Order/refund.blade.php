@extends('client.client_dashboard')

@section('content')

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Semua Pesanan Refund</h1>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Sl</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Tanggal Refund</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Alasan Refund</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($refunds as $key => $refund)
                <tr class="bg-white hover:bg-gray-300">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $key + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($refund->created_at)->format('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $refund->order->invoice_no }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $refund->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp {{ number_format($refund->order->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $refund->refund_reason }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($refund->status === 'pending')
                            <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">
                                Menunggu
                            </span>
                        @elseif($refund->status === 'rejected')
                            <span class="bg-red-500 text-white py-1 px-3 rounded-full text-sm">
                                Ditolak
                            </span>
                        @elseif($refund->status === 'accepted')
                            <span class="bg-blue-500 text-white py-1 px-3 rounded-full text-sm">
                                Diterima
                            </span>
                        @elseif($refund->status === 'completed')
                            <span class="bg-green-500 text-white py-1 px-3 rounded-full text-sm">
                                Selesai
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($refund->status === 'pending')
                            <div class="flex space-x-2">
                                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm execute-btn"
                                        data-refund-id="{{ $refund->id }}">
                                    Terima
                                </button>
                                <button type="button" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm reject-btn"
                                        data-refund-id="{{ $refund->id }}">
                                    Tolak
                                </button>
                            </div>
                        @elseif($refund->status === 'accepted')
                            <div class="flex space-x-2">
                                <button type="button" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-sm accept-btn"
                                        data-refund-id="{{ $refund->id }}">
                                    Diterima
                                </button>
                            </div>
                        @elseif($refund->status === 'completed')
                            <span class="text-green-600 font-semibold">Selesai</span>
                        @elseif($refund->status === 'rejected')
                            <span class="text-red-600 font-semibold">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal untuk alasan penolakan -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Alasan Penolakan Refund</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="reject_reason" id="reject_reason" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Masukkan alasan penolakan..." required></textarea>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" id="cancelReject"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle reject button
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', () => {
            const refundId = button.getAttribute('data-refund-id');
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');

            form.action = `/client/refund/reject/${refundId}`;
            modal.classList.remove('hidden');
        });
    });

    // Handle accept button
    document.querySelectorAll('.accept-btn').forEach(button => {
        button.addEventListener('click', () => {
            const refundId = button.getAttribute('data-refund-id');
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda sudah menerima produk yang dikembalikan atau refund?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesai',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dan submit untuk complete refund
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/client/refund/complete/${refundId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // Handle execute button (Eksekusi)
    document.querySelectorAll('.execute-btn').forEach(button => {
        button.addEventListener('click', () => {
            const refundId = button.getAttribute('data-refund-id');
            Swal.fire({
                title: 'Konfirmasi',
                text: 'apakah anda yakin ingin merima refund dari pesanan ini',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form dan submit untuk eksekusi refund
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/client/refund/execute/${refundId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // Handle cancel reject
    document.getElementById('cancelReject').addEventListener('click', () => {
        document.getElementById('rejectModal').classList.add('hidden');
    });

    // SweetAlert untuk pesan sukses/error
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
    @endif

    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: '{{ session('info') }}',
            showConfirmButton: true
        });
    @endif
</script>

@endsection
