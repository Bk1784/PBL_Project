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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider">Jumlah</th>

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
                        {{ $refund->orderItem->product->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Rp {{ number_format($refund->order->total_price, 0, ',', '.') }}
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
                                <button type="button"
                                    class="bg-purple-500 hover:bg-purple-600 text-white py-1 px-3 rounded text-sm evaluasi-btn"
                                    data-refund-id="{{ $refund->id }}"
                                    data-refund-reason="{{ $refund->refund_reason }}"
                                    data-refund-image="{{ $refund->refund_image ? Storage::url($refund->refund_image) : '' }}"
                                    data-refund-qty="{{ $refund->refund_qty }}"
                                    data-product-name="{{ $refund->orderItem->product->name ?? 'N/A' }}">
                                    Evaluasi
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

    // Handle evaluasi button
document.querySelectorAll('.evaluasi-btn').forEach(button => {
    button.addEventListener('click', () => {
        const refundId = button.getAttribute('data-refund-id');
        const refundReason = button.getAttribute('data-refund-reason');
        const refundImage = button.getAttribute('data-refund-image');
        const refundQty = button.getAttribute('data-refund-qty'); // ambil jumlah produk
        const productName = button.getAttribute('data-product-name');

        let htmlContent = `
            <div style="text-align: left; max-width: 500px; margin: 0 auto;">
                <!-- Header Info -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 12px; margin-bottom: 20px; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                    <h3 style="margin: 0; font-size: 18px; font-weight: bold;">📋 Evaluasi Refund</h3>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Detail lengkap permohonan refund</p>
                </div>

                <!-- Product Info Card -->
                <div style="background: #f8f9fa; border: 2px solid #e9ecef; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="border-left: 4px solid #007bff; padding-left: 15px; margin-bottom: 15px;">
                        <h4 style="margin: 0 0 10px 0; color: #007bff; font-size: 16px;">📦 Informasi Produk</h4>
                    </div>
                    <div style="padding: 10px 0; border-bottom: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #495057; display: block; margin-bottom: 5px;">Produk:</span>
                        <div style="background: #e3f2fd; padding: 8px; border-radius: 6px; color: #1976d2; font-weight: bold;">${productName}</div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
                        <span style="font-weight: 600; color: #495057;">Jumlah Refund:</span>
                        <span style="background: #007bff; color: #fff; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;">${refundQty} unit</span>
                    </div>
                </div>

                <!-- Refund Details Card -->
                <div style="background: #fff3cd; border: 2px solid #ffeaa7; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="border-left: 4px solid #ffc107; padding-left: 15px; margin-bottom: 15px;">
                        <h4 style="margin: 0 0 10px 0; color: #856404; font-size: 16px;">🔄 Detail Refund</h4>
                    </div>
                    <div style="padding: 10px 0;">
                        <span style="font-weight: 600; color: #856404; display: block; margin-bottom: 8px;">Alasan Refund:</span>
                        <div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #ffeaa7; color: #495057; line-height: 1.5;">${refundReason}</div>
                    </div>
                </div>

                <!-- Evidence Section -->
                ${refundImage ? `
                    <div style="background: #d1ecf1; border: 2px solid #bee5eb; border-radius: 12px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <div style="border-left: 4px solid #17a2b8; padding-left: 15px; margin-bottom: 15px;">
                            <h4 style="margin: 0 0 10px 0; color: #0c5460; font-size: 16px;">📷 Bukti Refund</h4>
                        </div>
                        <div style="text-align: center;">
                            <img src="${refundImage}" alt="Bukti Refund" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 3px solid #17a2b8; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);">
                        </div>
                    </div>
                ` : ''}

                <!-- Action Info -->
                <div style="background: #d4edda; border: 2px solid #c3e6cb; border-radius: 12px; padding: 15px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="border-left: 4px solid #28a745; padding-left: 15px; display: inline-block;">
                        <span style="color: #155724; font-size: 14px; font-weight: 500;">⚡ Pilih tindakan: Terima atau Tolak refund</span>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            title: 'Evaluasi Refund',
            html: htmlContent,
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Terima',
            denyButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            background: '#ffffff',
            backdrop: `rgba(0,0,0,0.4)`,
            width: '600px',
            padding: '20px',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
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
            } else if (result.isDenied) {
                const modal = document.getElementById('rejectModal');
                const form = document.getElementById('rejectForm');
                form.action = `/client/refund/reject/${refundId}`;
                modal.classList.remove('hidden');
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
