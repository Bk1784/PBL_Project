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
                               data-qty="{{ $maxQty }}"
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
        const maxQty = parseInt(button.getAttribute('data-qty'), 10) || 1;

        Swal.fire({
            title: 'Alasan Refund',
            html: `
                <textarea id="refund_reason" rows="4" placeholder="Masukkan alasan refund..." class="swal2-textarea" required></textarea>
                <input type="file" id="refund_image" accept="image/*" class="swal2-file" style="margin-top: 10px;">
                <div style="margin-top:15px;display:flex;align-items:center;gap:8px;">
                    <button type="button" id="qty_minus" style="background:#ef4444;color:white;border:none;padding:6px 14px;border-radius:4px;font-weight:bold;">-</button>
                    <span id="qty_value" style="min-width:30px;display:inline-block;text-align:center;font-weight:bold;">1</span>
                    <button type="button" id="qty_plus" style="background:#3b82f6;color:white;border:none;padding:6px 14px;border-radius:4px;font-weight:bold;">+</button>
                    <button type="button" id="qty_max" style="background:#a21caf;color:white;border:none;padding:6px 14px;border-radius:4px;font-weight:bold;">Max</button>
                    <span id="qty_help_text" style="margin-left:8px;">(max: ${maxQty})</span>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
            didOpen: () => {
                const popup = Swal.getPopup();
                const minusBtn = popup.querySelector('#qty_minus');
                const plusBtn  = popup.querySelector('#qty_plus');
                const maxBtn   = popup.querySelector('#qty_max');
                const qtyValue = popup.querySelector('#qty_value');
                const helpText = popup.querySelector('#qty_help_text');

                // helper: update disabled state berdasarkan value & maxQty
                function updateButtons() {
                    let current = parseInt(qtyValue.textContent, 10) || 1;
                    // min 1
                    if (current <= 1) {
                        minusBtn.disabled = true;
                        minusBtn.style.opacity = '0.6';
                        minusBtn.style.cursor = 'not-allowed';
                    } else {
                        minusBtn.disabled = false;
                        minusBtn.style.opacity = '';
                        minusBtn.style.cursor = '';
                    }

                    if (current >= maxQty) {
                        plusBtn.disabled = true;
                        plusBtn.style.opacity = '0.6';
                        plusBtn.style.cursor = 'not-allowed';
                        maxBtn.disabled = true;
                        maxBtn.style.opacity = '0.6';
                        maxBtn.style.cursor = 'not-allowed';
                    } else {
                        plusBtn.disabled = false;
                        plusBtn.style.opacity = '';
                        plusBtn.style.cursor = '';
                        maxBtn.disabled = false;
                        maxBtn.style.opacity = '';
                        maxBtn.style.cursor = '';
                    }

                    // update help text just in case
                    helpText.textContent = `(max: ${maxQty})`;
                }

                // inisialisasi nilai & tombol
                qtyValue.textContent = '1';
                updateButtons();

                minusBtn.addEventListener('click', () => {
                    let current = parseInt(qtyValue.textContent, 10) || 1;
                    current = Math.max(1, current - 1);
                    qtyValue.textContent = current;
                    updateButtons();
                });

                plusBtn.addEventListener('click', () => {
                    let current = parseInt(qtyValue.textContent, 10) || 1;
                    current = Math.min(maxQty, current + 1);
                    qtyValue.textContent = current;
                    updateButtons();
                });

                maxBtn.addEventListener('click', () => {
                    qtyValue.textContent = maxQty;
                    updateButtons();
                });

                // Jika maxQty <= 1: langsung disable plus & max
                if (maxQty <= 1) {
                    plusBtn.disabled = true;
                    plusBtn.style.opacity = '0.6';
                    plusBtn.style.cursor = 'not-allowed';
                    maxBtn.disabled = true;
                    maxBtn.style.opacity = '0.6';
                    maxBtn.style.cursor = 'not-allowed';
                }
            },
            preConfirm: () => {
                const reasonEl = Swal.getPopup().querySelector('#refund_reason');
                const imageEl = Swal.getPopup().querySelector('#refund_image');
                const qtyEl = Swal.getPopup().querySelector('#qty_value');

                const reason = reasonEl ? reasonEl.value.trim() : '';
                const image = imageEl ? imageEl.files[0] : null;
                const qty = parseInt(qtyEl ? qtyEl.textContent : '1', 10) || 1;

                if (!reason) {
                    Swal.showValidationMessage('Alasan refund harus diisi');
                    return false;
                }

                // tambahan validasi: qty harus antara 1..maxQty
                if (qty < 1 || qty > maxQty) {
                    Swal.showValidationMessage(`Jumlah refund harus antara 1 dan ${maxQty}`);
                    return false;
                }

                return { reason, image, qty };
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            const { reason, image, qty } = result.value;

            const formData = new FormData();
            formData.append('refund_reason', reason);
            if (image) {
                formData.append('refund_image', image);
            }
            formData.append('refund_qty', qty);
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
