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
                                onclick="showAcceptedAlert({{ $refund->id }})"
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
    function showAcceptedAlert(refundId) {
        Swal.fire({
            title: '<i class="fas fa-credit-card" style="color: #4F46E5;"></i> Rekening Penerima Dana Refund',
            html: `
                <style>
                    .banking-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 25px;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                        animation: fadeInUp 0.5s ease-out;
                    }
                    .banking-table th {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 15px 12px;
                        text-align: left;
                        font-weight: 600;
                        font-size: 14px;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    .banking-table td {
                        padding: 12px;
                        border-bottom: 1px solid #e9ecef;
                        background: #fff;
                        transition: all 0.3s ease;
                    }
                    .banking-table tr:hover td {
                        background: #f8f9ff;
                        transform: scale(1.01);
                    }
                    .delete-btn {
                        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
                        color: white;
                        border: none;
                        padding: 6px 12px;
                        border-radius: 20px;
                        cursor: pointer;
                        font-size: 12px;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
                    }
                    .delete-btn:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
                    }
                    .form-section {
                        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                        padding: 25px;
                        border-radius: 15px;
                        margin-bottom: 20px;
                        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.2);
                        animation: slideInRight 0.6s ease-out;
                    }
                    .form-group {
                        margin-bottom: 20px;
                        animation: fadeIn 0.8s ease-out;
                    }
                    .form-label {
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 600;
                        color: #2d3748;
                        font-size: 14px;
                    }
                    .form-input, .form-select {
                        width: 100%;
                        padding: 12px 15px;
                        border: 2px solid #e2e8f0;
                        border-radius: 10px;
                        font-size: 14px;
                        transition: all 0.3s ease;
                        background: rgba(255, 255, 255, 0.9);
                        backdrop-filter: blur(10px);
                    }
                    .form-input:focus, .form-select:focus {
                        border-color: #667eea;
                        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                        transform: translateY(-2px);
                    }
                    .empty-state {
                        text-align: center;
                        padding: 40px 20px;
                        color: #a0aec0;
                        font-style: italic;
                        animation: fadeIn 0.5s ease-out;
                    }
                    .empty-state i {
                        font-size: 48px;
                        margin-bottom: 15px;
                        opacity: 0.5;
                    }
                    @keyframes fadeInUp {
                        from { opacity: 0; transform: translateY(30px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    @keyframes slideInRight {
                        from { opacity: 0; transform: translateX(50px); }
                        to { opacity: 1; transform: translateX(0); }
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                </style>

                <div id="existingBankingData" style="margin-bottom: 25px;">
                    <h4 style="margin-bottom: 15px; color: #4F46E5; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-list" style="margin-right: 8px;"></i>
                        Rekening Tersimpan
                    </h4>
                    <table class="banking-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Nama Penerima</th>
                                <th><i class="fas fa-university"></i> Bank/E-Wallet</th>
                                <th><i class="fas fa-hashtag"></i> Nomor Rekening</th>
                                <th><i class="fas fa-trash"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bankingTableBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="form-section">
                    <h4 style="margin-bottom: 20px; color: white; font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-plus-circle" style="margin-right: 8px;"></i>
                        Tambah Rekening Baru
                    </h4>
                    <div class="form-group">
                        <label class="form-label" for="nama_penerima">
                            <i class="fas fa-user-tag"></i> Nama Pemilik Rekening
                        </label>
                        <input type="text" id="nama_penerima" class="form-input" placeholder="Masukkan nama pemilik rekening">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="bank_ewallet">
                            <i class="fas fa-building"></i> Pilih Bank/E-Wallet
                        </label>
                        <select id="bank_ewallet" class="form-select">
                            <option value="">Pilih Bank/E-Wallet</option>
                            <option value="BCA">🏦 BCA</option>
                            <option value="BNI">🏦 BNI</option>
                            <option value="BRI">🏦 BRI</option>
                            <option value="Mandiri">🏦 Mandiri</option>
                            <option value="Permata">🏦 Permata</option>
                            <option value="BSI">🏦 BSI</option>
                            <option value="Gopay">📱 Gopay</option>
                            <option value="OVO">📱 OVO</option>
                            <option value="DANA">📱 DANA</option>
                            <option value="LinkAja">📱 LinkAja</option>
                            <option value="ShopeePay">📱 ShopeePay</option>
                            <option value="QRIS">📱 QRIS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="nomor_rekening">
                            <i class="fas fa-mobile-alt"></i> Nomor Rekening/HP
                        </label>
                        <input type="text" id="nomor_rekening" class="form-input" placeholder="Masukkan nomor rekening atau nomor HP">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save"></i> Simpan Rekening',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#6c757d',
            width: '800px',
            padding: '30px',
            background: '#f8fafc',
            backdrop: `rgba(79, 70, 229, 0.1)`,
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutDown animate__faster'
            },
            preConfirm: () => {
                const namaPenerima = document.getElementById('nama_penerima').value;
                const bankEwallet = document.getElementById('bank_ewallet').value;
                const nomorRekening = document.getElementById('nomor_rekening').value;

                if (!namaPenerima || !bankEwallet || !nomorRekening) {
                    Swal.showValidationMessage('<i class="fas fa-exclamation-triangle"></i> Semua field harus diisi!');
                    return false;
                }

                return { namaPenerima, bankEwallet, nomorRekening };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { namaPenerima, bankEwallet, nomorRekening } = result.value;

                // Kirim data ke server
                fetch(`/customer/refund/${refundId}/banking`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama_penerima: namaPenerima,
                        bank_ewallet: bankEwallet,
                        nomor_rekening: nomorRekening
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<i class="fas fa-check-circle"></i> Berhasil!',
                            text: 'Data rekening berhasil disimpan.',
                            confirmButtonColor: '#4F46E5',
                            timer: 3000,
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            }
                        });
                        // Reload halaman atau update tabel
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '<i class="fas fa-times-circle"></i> Gagal!',
                            text: data.message || 'Terjadi kesalahan.',
                            confirmButtonColor: '#e53e3e'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '<i class="fas fa-exclamation-triangle"></i> Error!',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                        confirmButtonColor: '#e53e3e'
                    });
                });
            }
        });

        // Load existing banking data
        fetch(`/customer/refund/${refundId}/banking`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('bankingTableBody');
                if (data && data.length > 0) {
                    tbody.innerHTML = data.map(item => `
                        <tr style="animation: fadeIn 0.5s ease-out;">
                            <td><i class="fas fa-user-circle" style="color: #4F46E5; margin-right: 8px;"></i>${item.nama_penerima}</td>
                            <td><i class="fas fa-building" style="color: #48bb78; margin-right: 8px;"></i>${item.bank_ewallet}</td>
                            <td><i class="fas fa-hashtag" style="color: #ed8936; margin-right: 8px;"></i>${item.nomor_rekening}</td>
                            <td>
                                <button class="delete-btn" onclick="deleteBankingData(${refundId}, ${item.id})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="empty-state">
                                <i class="fas fa-inbox"></i><br>
                                Belum ada data rekening yang tersimpan
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading banking data:', error);
            });
    }

    function deleteBankingData(refundId, bankingId) {
        Swal.fire({
            title: '<i class="fas fa-trash-alt" style="color: #e53e3e;"></i> Hapus Rekening',
            text: 'Apakah Anda yakin ingin menghapus data rekening ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/customer/refund/${refundId}/banking/${bankingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '<i class="fas fa-check-circle"></i> Terhapus!',
                            text: 'Data rekening berhasil dihapus.',
                            confirmButtonColor: '#4F46E5',
                            timer: 3000,
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            }
                        });
                        // Reload halaman untuk update tabel
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '<i class="fas fa-times-circle"></i> Gagal!',
                            text: data.message || 'Terjadi kesalahan.',
                            confirmButtonColor: '#e53e3e'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '<i class="fas fa-exclamation-triangle"></i> Error!',
                        text: 'Terjadi kesalahan saat menghapus data.',
                        confirmButtonColor: '#e53e3e'
                    });
                });
            }
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
