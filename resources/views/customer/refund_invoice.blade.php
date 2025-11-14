<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Refund Invoice #{{ $refund->order->invoice_no }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: bold;
        }
        .company-info {
            margin-bottom: 10px;
            font-size: 12px;
        }
        .company-info h2 {
            color: #4F46E5;
            margin: 0;
            font-size: 18px;
        }
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-weight: bold;
        }
        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-details th, .invoice-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .invoice-details th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .section-title {
            color: #4F46E5;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .product-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .product-table .amount {
            font-weight: bold;
            color: #4F46E5;
        }
        .reason-section p {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 3px solid #4F46E5;
            line-height: 1.5;
        }
        .image-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .image-section img {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ddd;
        }
        .status {
            padding: 12px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }
        .status.accepted {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #17a2b8;
        }
        .status.rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #dc3545;
        }
        .status.completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .price {
            font-weight: bold;
            color: #4F46E5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h2>Galaxy Store</h2>
            <p>Address: Srono, Banyuwangi</p>
            <p>Email: @galaxystore.com | Phone: +62 812-3456-7890</p>
        </div>
        <h1>Refund Invoice</h1>
        <div class="invoice-meta">
            <span>Invoice No: {{ $refund->order->invoice_no }}</span>
            <span>Date: {{ $refund->created_at->format('d F Y') }}</span>
        </div>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <th>Customer</th>
                <td>{{ $refund->user->name }}</td>
            </tr>
            <tr>
                <th>Order Date</th>
                <td>{{ $refund->order->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <th>Total Order Amount</th>
                <td class="price">Rp {{ number_format($refund->order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <h3 class="section-title">Detail Produk Refund</h3>
    <table class="product-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity Refunded</th>
                <th>Unit Price</th>
                <th>Refund Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $refund->orderItem->product->name ?? 'N/A' }}</td>
                <td>{{ $refund->refund_qty }}</td>
                <td>Rp {{ number_format($refund->orderItem->price ?? 0, 0, ',', '.') }}</td>
                <td class="amount">Rp {{ number_format(($refund->refund_qty * ($refund->orderItem->price ?? 0)), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="reason-evidence-container">
        <div class="reason-section">
            <h3 class="section-title">Alasan Refund</h3>
            <p>{{ $refund->refund_reason }}</p>
        </div>
        @if($refund->refund_image)
        <div class="evidence-section">
            <h3 class="section-title">Bukti Refund</h3>
            <div class="image-section">
                <img src="{{ str_starts_with($refund->refund_image, 'upload/') ? asset($refund->refund_image) : asset('storage/' . $refund->refund_image) }}" height="150px" width="150px" alt="Refund Image">
            </div>
        </div>
        @endif
    </div>

    <div class="status {{ strtolower($refund->status) }}">
        <h3>Status Refund: {{ $refund->status === 'pending' ? 'Menunggu' : ($refund->status === 'accepted' ? 'Diterima' : ($refund->status === 'rejected' ? 'Ditolak' : ($refund->status === 'completed' ? 'Selesai' : ucfirst($refund->status)))) }}</h3>
        @if($refund->status == 'rejected' && $refund->reject_reason)
            <p>Alasan Penolakan: {{ $refund->reject_reason }}</p>
        @endif
        @if($refund->refunded_at)
            <p>Tanggal Refund: {{ $refund->refunded_at->format('d F Y') }}</p>
        @endif
    </div>

    <div class="footer">
        <p>mohon maaf atas ketidaknyamanan yang sampeyan rasakan</p>
        <p>barang siapa memaafkan kesalahan orang lain, maka dia baik</p>
    </div>
</body>
</html>
