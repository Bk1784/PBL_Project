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
                            @php
                                // hitung max qty dari order items (fallback ke order->qty atau 1)
                                $maxQty = null;
                                if (method_exists($order, 'orderItems')) {
                                    $maxQty = $order->orderItems->sum('qty');
                                } elseif (isset($order->items)) {
                                    $maxQty = collect($order->items)->sum('qty');
                                }
                                $maxQty = $maxQty ?: ($order->qty ?? 1);
                            @endphp

                            <a href="javascript:void(0);"
                               class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded refund-btn"
                               data-order-id="{{ $order->id }}"
                               data-order-items='{{ $order->orderItems->toJson() }}'
                               title="Refund">
                                <i class="fas fa-undo"></i>
                            </a>
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

<!-- SweetAlert untuk input alasan refund -->
<script>
document.querySelectorAll('.refund-btn').forEach(button => {
    button.addEventListener('click', () => {
        const orderId = button.getAttribute('data-order-id');
        const orderItems = JSON.parse(button.getAttribute('data-order-items'));

        // Build HTML for product selection
        let productsHtml = '<div style="max-height: 350px; overflow-y: auto;">';
        orderItems.forEach(item => {
            productsHtml += `
                <div class="product-item">
                    <div class="product-header">
                        <input type="checkbox" class="product-checkbox" data-item-id="${item.id}" data-max-qty="${item.qty}">
                        <div class="product-info">
                            <div class="product-name">${item.product.name}</div>
                            <div class="product-price">Rp ${parseInt(item.price).toLocaleString()}</div>
                        </div>
                    </div>
                    <div class="refund-details">
                    <div class="qty-controls">
                        <span class="qty-label">Jumlah:</span>
                        <button type="button" class="qty-btn qty-minus">-</button>
                        <input type="number" class="qty-input" value="1" min="1" max="${item.qty}" readonly>
                        <button type="button" class="qty-btn qty-plus">+</button>
                        <span style="font-size: 11px; color: #6b7280; margin-left: 4px;">(max: ${item.qty})</span>
                    </div>
                        <div class="reason-section">
                            <span class="reason-label">Alasan Refund:</span>
                            <textarea class="refund-reason" placeholder="Jelaskan alasan refund..." rows="2"></textarea>
                        </div>
                        <div class="image-section">
                            <span class="image-label">Bukti (opsional):</span>
                            <input type="file" class="refund-image" accept="image/*">
                        </div>
                    </div>
                </div>
            `;
        });
        productsHtml += '</div>';

        Swal.fire({
            title: '<i class="fas fa-undo-alt" style="color: #ef4444;"></i> Refund Produk',
            html: `
                <style>
                    .refund-container {
                        text-align: left;
                        max-width: 100%;
                    }
                    .product-item {
                        border: 1px solid #e5e7eb;
                        border-radius: 10px;
                        padding: 12px;
                        margin-bottom: 12px;
                        background: #ffffff;
                        transition: all 0.2s ease;
                    }
                    .product-item:hover {
                        border-color: #ef4444;
                        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
                    }
                    .product-header {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        margin-bottom: 8px;
                    }
                    .product-checkbox {
                        width: 18px;
                        height: 18px;
                        accent-color: #ef4444;
                    }
                    .product-info {
                        flex: 1;
                        font-size: 14px;
                    }
                    .product-name {
                        font-weight: 600;
                        color: #111827;
                        margin-bottom: 2px;
                    }
                    .product-price {
                        color: #6b7280;
                        font-size: 13px;
                    }
                    .refund-details {
                        display: none;
                        margin-top: 10px;
                        padding-top: 10px;
                        border-top: 1px solid #e5e7eb;
                    }
                    .qty-controls {
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        margin-bottom: 8px;
                    }
                    .qty-label {
                        font-size: 13px;
                        font-weight: 500;
                        color: #374151;
                    }
                    .qty-btn {
                        background: #f3f4f6;
                        color: #374151;
                        border: 1px solid #d1d5db;
                        width: 24px;
                        height: 24px;
                        border-radius: 4px;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 12px;
                        transition: all 0.2s ease;
                    }
                    .qty-btn:hover {
                        background: #ef4444;
                        color: white;
                        border-color: #ef4444;
                    }
                    .qty-input {
                        width: 50px;
                        text-align: center;
                        border: 1px solid #d1d5db;
                        border-radius: 4px;
                        padding: 4px;
                        font-size: 13px;
                        font-weight: 500;
                    }
                    .reason-section {
                        margin-top: 8px;
                    }
                    .reason-label {
                        font-size: 13px;
                        font-weight: 500;
                        color: #374151;
                        margin-bottom: 4px;
                        display: block;
                    }
                    .refund-reason {
                        width: 100%;
                        border: 1px solid #d1d5db;
                        border-radius: 6px;
                        padding: 8px;
                        font-size: 13px;
                        resize: vertical;
                        min-height: 50px;
                        transition: border-color 0.2s ease;
                    }
                    .refund-reason:focus {
                        border-color: #ef4444;
                        outline: none;
                    }
                    .image-section {
                        margin-top: 8px;
                    }
                    .image-label {
                        font-size: 12px;
                        color: #6b7280;
                        margin-bottom: 4px;
                        display: block;
                    }
                    .refund-image {
                        width: 100%;
                        padding: 6px;
                        border: 1px dashed #d1d5db;
                        border-radius: 6px;
                        background: #f9fafb;
                        font-size: 12px;
                        transition: border-color 0.2s ease;
                    }
                    .refund-image:hover {
                        border-color: #ef4444;
                    }
                    .instruction {
                        background: #f0f9ff;
                        border: 1px solid #bae6fd;
                        border-radius: 8px;
                        padding: 10px;
                        margin-bottom: 15px;
                        text-align: center;
                        font-size: 13px;
                        color: #0369a1;
                    }
                    .instruction i {
                        color: #0284c7;
                        margin-right: 6px;
                    }
                </style>
                <div class="refund-container">
                    <div class="instruction">
                        <i class="fas fa-info-circle"></i>
                        Pilih produk, tentukan jumlah, berikan alasan refund, dan sertakan bukti refund
                    </div>
                    ${productsHtml}
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Kirim Refund',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            width: '700px',
            padding: '25px',
            background: '#ffffff',
            backdrop: `rgba(0,0,0,0.4)`,
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            didOpen: () => {
                const popup = Swal.getPopup();

                // Handle checkbox changes
                popup.querySelectorAll('.product-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', (e) => {
                        const refundDetails = e.target.closest('.product-item').querySelector('.refund-details');
                        if (e.target.checked) {
                            refundDetails.style.display = 'block';
                        } else {
                            refundDetails.style.display = 'none';
                        }
                    });
                });

                // Handle qty buttons
                popup.querySelectorAll('.qty-minus').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const input = e.target.closest('.qty-controls').querySelector('.qty-input');
                        const max = parseInt(input.max);
                        let val = parseInt(input.value) || 1;
                        val = Math.max(1, val - 1);
                        input.value = val;
                    });
                });

                popup.querySelectorAll('.qty-plus').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const input = e.target.closest('.qty-controls').querySelector('.qty-input');
                        const max = parseInt(input.max);
                        let val = parseInt(input.value) || 1;
                        val = Math.min(max, val + 1);
                        input.value = val;
                    });
                });
            },
            preConfirm: () => {
                const popup = Swal.getPopup();

                // Collect selected products with their refund reason and image
                const selectedItems = [];
                let validationError = null;

                for (const checkbox of popup.querySelectorAll('.product-checkbox:checked')) {
                    const itemId = parseInt(checkbox.getAttribute('data-item-id'));
                    const qtyInput = checkbox.closest('.product-item').querySelector('.qty-input');
                    const qty = parseInt(qtyInput.value) || 1;
                    const maxQty = parseInt(checkbox.getAttribute('data-max-qty'));

                    const reasonEl = checkbox.closest('.product-item').querySelector('.refund-reason');
                    const imageEl = checkbox.closest('.product-item').querySelector('.refund-image');

                    const reason = reasonEl ? reasonEl.value.trim() : '';
                    const image = imageEl ? imageEl.files[0] : null;

                    if (qty < 1 || qty > maxQty) {
                        validationError = `Jumlah refund untuk produk harus antara 1 dan ${maxQty}`;
                        break;
                    }

                    if (!reason) {
                        validationError = 'Alasan refund harus diisi untuk setiap produk yang dipilih';
                        break;
                    }

                    selectedItems.push({ order_item_id: itemId, qty: qty, refund_reason: reason, refund_image: image });
                }

                if (validationError) {
                    Swal.showValidationMessage(validationError);
                    return false;
                }

                if (selectedItems.length === 0) {
                    Swal.showValidationMessage('Pilih setidaknya satu produk untuk refund');
                    return false;
                }

                return { refund_items: selectedItems };
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            const { refund_items } = result.value;

            const formData = new FormData();

            refund_items.forEach((item, index) => {
                formData.append(`refund_items[${index}][order_item_id]`, item.order_item_id);
                formData.append(`refund_items[${index}][qty]`, item.qty);
                formData.append(`refund_items[${index}][refund_reason]`, item.refund_reason);
                if (item.refund_image) {
                    formData.append(`refund_items[${index}][refund_image]`, item.refund_image);
                }
            });

            formData.append('_token', '{{ csrf_token() }}');

            const url = "{{ url('/orders') }}/" + orderId + "/refund";

            fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                let data = null;
                try {
                    data = text ? JSON.parse(text) : null;
                } catch (e) {}
                if (!response.ok) {
                    const msg = (data && data.message) ? data.message : (text || `HTTP ${response.status}`);
                    throw new Error(msg);
                }
                return data;
            })
            .then(data => {
                if (data && data.success) {
                    Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
                } else {
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
