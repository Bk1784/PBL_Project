@extends('client.client_dashboard')

@section('content')

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>

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
                                    data-refund-image="{{ $refund->refund_image ? (str_starts_with($refund->refund_image, 'upload/') ? asset($refund->refund_image) : asset('storage/' . $refund->refund_image)) : '' }}"
                                    data-refund-qty="{{ $refund->refund_qty }}"
                                    data-product-name="{{ $refund->orderItem->product->name ?? 'N/A' }}">
                                    Evaluasi
                                </button>


                            </div>
                        @elseif($refund->status === 'accepted')
                            <div class="flex space-x-2">
                            <button type="button" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded text-sm accept-btn"
                                        data-refund-id="{{ $refund->id }}"
                                        data-refund-reason="{{ $refund->refund_reason }}"
                                        data-refund-image="{{ $refund->refund_image ? (str_starts_with($refund->refund_image, 'upload/') ? asset($refund->refund_image) : asset('storage/' . $refund->refund_image)) : '' }}"
                                        data-refund-qty="{{ $refund->refund_qty }}"
                                        data-product-name="{{ $refund->orderItem->product->name ?? 'N/A' }}"
                                        data-refund-amount="{{ ($refund->orderItem->price ?? 0) * $refund->refund_qty }}">
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
            const refundReason = button.getAttribute('data-refund-reason');
            const refundImage = button.getAttribute('data-refund-image');
            const refundQty = button.getAttribute('data-refund-qty');
            const productName = button.getAttribute('data-product-name');
            const refundAmount = parseInt(button.getAttribute('data-refund-amount')) || 0;

            // Fetch banking data first
            fetch(`/customer/refund/${refundId}/banking`)
                .then(response => response.json())
                .then(data => {
                    let tableContent = '';
                    let qrData = null;
                    if (data && data.length > 0) {
                        tableContent = data.map((item, index) => `
                            <tr onclick="generateQRCode('${item.nama_penerima}', '${item.bank_ewallet}', '${item.nomor_rekening}', ${index})">
                                <td><i class="fas fa-user-circle" style="color: #4F46E5; margin-right: 8px;"></i>${item.nama_penerima}</td>
                                <td><i class="fas fa-building" style="color: #48bb78; margin-right: 8px;"></i>${item.bank_ewallet}</td>
                                <td><i class="fas fa-hashtag" style="color: #ed8936; margin-right: 8px;"></i>${item.nomor_rekening}</td>
                            </tr>
                        `).join('');
                        // Set QR data for the first rekening by default
                        if (data.length > 0) {
                            qrData = data[0];
                        }
                    } else {
                        tableContent = `
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <i class="fas fa-inbox"></i><br>
                                    Belum ada data rekening yang tersimpan
                                </td>
                            </tr>
                        `;
                    }

                    Swal.fire({
                        title: '<i class="fas fa-check-circle" style="color: #10B981;"></i> Konfirmasi & Rekening Penerima',
                        html: `
                            <style>
                                .confirmation-section {
                                    background: linear-gradient(135deg, #10B981, #059669);
                                    color: white;
                                    padding: 20px;
                                    border-radius: 15px;
                                    margin-bottom: 25px;
                                    text-align: center;
                                    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
                                    animation: slideInFromTop 0.6s ease-out;
                                }
                                .confirmation-section h3 {
                                    margin: 0 0 10px 0;
                                    font-size: 18px;
                                    font-weight: bold;
                                }
                                .confirmation-section p {
                                    margin: 0;
                                    font-size: 16px;
                                    opacity: 0.9;
                                }
                                .banking-section {
                                    background: #f8fafc;
                                    border: 2px solid #e2e8f0;
                                    border-radius: 15px;
                                    padding: 20px;
                                    margin-bottom: 20px;
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                                    animation: slideInFromBottom 0.6s ease-out;
                                }
                                .banking-section h4 {
                                    margin: 0 0 15px 0;
                                    color: #4F46E5;
                                    font-weight: bold;
                                    font-size: 16px;
                                    display: flex;
                                    align-items: center;
                                }
                                .banking-section h4 i {
                                    margin-right: 8px;
                                }
                                .banking-table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    border-radius: 10px;
                                    overflow: hidden;
                                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                }
                                .banking-table th {
                                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    color: white;
                                    padding: 12px 10px;
                                    text-align: left;
                                    font-weight: 600;
                                    font-size: 14px;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                }
                                .banking-table td {
                                    padding: 10px;
                                    border-bottom: 1px solid #e9ecef;
                                    background: #fff;
                                    transition: all 0.3s ease;
                                }
                                .banking-table tr {
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                }
                                .banking-table tr:hover {
                                    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                                    transform: translateX(5px);
                                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
                                }
                                .banking-table tr:hover td {
                                    color: #1e40af;
                                    font-weight: 500;
                                }
                                .banking-table tr.selected {
                                    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
                                    border-left: 4px solid #3b82f6;
                                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
                                }
                                .banking-table tr.selected td {
                                    color: #1e40af;
                                    font-weight: 600;
                                }
                                .banking-table tr:last-child td {
                                    border-bottom: none;
                                }
                                .qr-code-container {
                                    text-align: center;
                                    margin-top: 15px;
                                    padding: 15px;
                                    background: white;
                                    border-radius: 12px;
                                    display: inline-block;
                                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                                    animation: slideInFromRight 0.6s ease-out;
                                }
                                .qr-code-container img {
                                    max-width: 120px;
                                    border-radius: 8px;
                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                }
                                .qr-btn {
                                    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                                    border: none;
                                    color: white;
                                    padding: 6px 12px;
                                    border-radius: 20px;
                                    font-size: 12px;
                                    font-weight: 500;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                    margin-top: 8px;
                                    box-shadow: 0 2px 8px rgba(106, 17, 203, 0.3);
                                }
                                .qr-btn:hover {
                                    transform: translateY(-2px);
                                    box-shadow: 0 4px 15px rgba(106, 17, 203, 0.4);
                                }
                                .empty-state {
                                    text-align: center;
                                    padding: 30px 20px;
                                    color: #a0aec0;
                                    font-style: italic;
                                    animation: fadeIn 0.5s ease-out;
                                }
                                .empty-state i {
                                    font-size: 40px;
                                    margin-bottom: 10px;
                                    opacity: 0.5;
                                }
                                @keyframes slideInFromTop {
                                    from { opacity: 0; transform: translateY(-30px); }
                                    to { opacity: 1; transform: translateY(0); }
                                }
                                @keyframes slideInFromBottom {
                                    from { opacity: 0; transform: translateY(30px); }
                                    to { opacity: 1; transform: translateY(0); }
                                }
                                @keyframes slideInFromRight {
                                    from { opacity: 0; transform: translateX(30px); }
                                    to { opacity: 1; transform: translateX(0); }
                                }
                                @keyframes fadeIn {
                                    from { opacity: 0; }
                                    to { opacity: 1; }
                                }
                            </style>

                            <div class="confirmation-section">
                                <h3><i class="fas fa-question-circle"></i> Konfirmasi Penyelesaian</h3>
                                <p>Apakah anda sudah menerima produk yang dikembalikan atau refund?</p>
                            </div>

                            <!-- Refund Details Section -->
                            <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 15px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <h4 style="margin: 0 0 15px 0; color: #4F46E5; font-weight: bold; font-size: 16px; display: flex; align-items: center;">
                                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i> Detail Refund
                                </h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <strong style="color: #374151;">Nama Produk:</strong><br>
                                        <span style="color: #6b7280;">${productName}</span>
                                    </div>
                                    <div>
                                        <strong style="color: #374151;">Jumlah Produk Refund:</strong><br>
                                        <span style="color: #6b7280;">${refundQty} item</span>
                                    </div>
                                    <div>
                                        <strong style="color: #374151;">Jumlah Dana Refund:</strong><br>
                                        <span style="color: #6b7280;">Rp ${refundAmount.toLocaleString('id-ID')}</span>
                                    </div>
                                    <div>
                                        <strong style="color: #374151;">Alasan Refund:</strong><br>
                                        <span style="color: #6b7280;">${refundReason}</span>
                                    </div>
                                </div>
                                ${refundImage ? `
                                    <div style="margin-top: 15px; text-align: center;">
                                        <strong style="color: #374151;">Bukti Refund:</strong><br>
                                        <img src="${refundImage}" alt="Bukti Refund" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0; margin-top: 10px;">
                                    </div>
                                ` : ''}
                            </div>

                            <div class="banking-section">
                                <h4><i class="fas fa-credit-card"></i> Rekening Penerima Dana Refund</h4>
                                <table class="banking-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-user"></i> Nama Penerima</th>
                                            <th><i class="fas fa-university"></i> Bank/E-Wallet</th>
                                            <th><i class="fas fa-hashtag"></i> Nomor Rekening</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tableContent}
                                    </tbody>
                                </table>
                                <div style="margin-top: 15px; padding: 10px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <p style="margin: 0; font-size: 13px; color: #64748b; text-align: center;">
                                        <i class="fas fa-mouse-pointer" style="color: #3b82f6; margin-right: 5px;"></i>
                                        Pilih rekening untuk generate QR code
                                    </p>
                                </div>
                                <div id="qrContainer" class="qr-code-container" style="display: none;">
                                    <p style="margin: 0 0 10px 0; font-size: 14px; color: #4F46E5; font-weight: bold;">
                                        <i class="fas fa-qrcode"></i> QR Code Pembayaran
                                    </p>
                                    <div id="qrCodeDisplay"></div>
                                    <button class="qr-btn" onclick="downloadQRCode()">
                                        <i class="fas fa-download"></i> Download QR
                                    </button>
                                </div>
                            </div>

                            <!-- Warning Section -->
                            <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 12px; padding: 15px; margin-top: 20px; text-align: center; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);">
                                <div style="border-left: 4px solid #f59e0b; padding-left: 15px; display: inline-block;">
                                    <p style="margin: 0; color: #92400e; font-size: 14px; font-weight: 500;">
                                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b; margin-right: 8px;"></i>
                                        Cek kembali transaksi anda dan pastikan jika transaksi sudah sukses tekan tombol Ya, Selesai untuk menyelasaikan pembayaran refund ini
                                    </p>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-check"></i> Ya, Selesai',
                        cancelButtonText: '<i class="fas fa-times"></i> Batal',
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6c757d',
                        width: '700px',
                        padding: '30px',
                        background: '#ffffff',
                        backdrop: `rgba(16, 185, 129, 0.1)`,
                        showClass: {
                            popup: 'animate__animated animate__fadeInUp animate__faster'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutDown animate__faster'
                        },
                        icon: 'question'
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

                    // QR code will be generated only when user clicks on a rekening row
                })
                .catch(error => {
                    console.error('Error loading banking data:', error);
                    Swal.fire('Error', 'Gagal memuat data rekening.', 'error');
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

    // Function to generate QR code
    function generateQRCode(nama, bank, nomor, index) {
        const qrData = `Bank/E-Wallet: ${bank}\nNomor: ${nomor}\nPemilik: ${nama}`;
        const qr = qrcode(0, 'M');
        qr.addData(qrData);
        qr.make();

        const qrCodeDataURL = qr.createDataURL(8, 0);
        const qrContainer = document.getElementById('qrContainer');
        const qrDisplay = document.getElementById('qrCodeDisplay');

        qrDisplay.innerHTML = `<img src="${qrCodeDataURL}" alt="QR Code" style="max-width: 120px;">`;
        qrContainer.style.display = 'block';

        // Store current QR data for download
        qrContainer.dataset.qrData = qrCodeDataURL;
        qrContainer.dataset.fileName = `QR-${bank}-${nomor}.png`;

        // Add visual feedback for selected row
        const rows = document.querySelectorAll('.banking-table tbody tr');
        rows.forEach((row, i) => {
            if (i === index) {
                row.classList.add('selected');
            } else {
                row.classList.remove('selected');
            }
        });
    }

    // Function to download QR code
    function downloadQRCode() {
        const qrContainer = document.getElementById('qrContainer');
        const qrDataURL = qrContainer.dataset.qrData;
        const fileName = qrContainer.dataset.fileName;

        if (qrDataURL) {
            const link = document.createElement('a');
            link.href = qrDataURL;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show download success notification
            Swal.fire({
                title: 'Berhasil!',
                text: 'QR Code berhasil diunduh',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10B981',
                timer: 2000,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
    }
</script>

@endsection
