<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Refund Invoice #{{ $refund->order->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #333; margin-bottom: 5px; }
        .company-info { margin-bottom: 20px; }
        .invoice-details { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .invoice-details th, .invoice-details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .invoice-details th { background-color: #f2f2f2; }
        .product-section { margin-bottom: 20px; }
        .reason-section { margin-bottom: 20px; background-color: #f9f9f9; padding: 15px; border-radius: 5px; }
        .image-section { text-align: center; margin-bottom: 20px; }
        .image-section img { max-width: 200px; max-height: 200px; border: 1px solid #ddd; }
        .status { padding: 10px; border-radius: 5px; text-align: center; font-weight: bold; }
        .status.pending { background-color: #fff3cd; color: #856404; }
        .status.accepted { background-color: #d1ecf1; color: #0c5460; }
        .status.rejected { background-color: #f8d7da; color: #721c24; }
        .status.completed { background-color: #d4edda; color: #155724; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h2>Galaxy Store</h2>
            <p>Adress: Srono, Banyuwangi</p>
            <p>Email: @galaxystore.com | Phone: +62 812-3456-7890</p>
        </div>
        <h1>Refund Invoice</h1>
        <p>Invoice No: {{ $refund->order->invoice_no }}</p>
        <p>Date: {{ $refund->created_at->format('d F Y') }}</p>
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
                <td>Rp {{ number_format($refund->order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="product-section">
        <h3>Refunded Product Details</h3>
        <table class="invoice-details">
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
                    <td>Rp {{ number_format(($refund->refund_qty * ($refund->orderItem->price ?? 0)), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="reason-section">
        <h3>Reason for Refund</h3>
        <p>{{ $refund->refund_reason }}</p>
    </div>

    @if($refund->refund_image)
    <div class="image-section">
        <h3>Refund Evidence</h3>
        <img src="{{ asset('storage/' . $refund->refund_image) }}" alt="Refund Image">
    </div>
    @endif

    <div class="status {{ strtolower($refund->status) }}">
        <h3>Refund Status: {{ ucfirst($refund->status) }}</h3>
        @if($refund->status == 'rejected' && $refund->reject_reason)
            <p>Rejection Reason: {{ $refund->reject_reason }}</p>
        @endif
        @if($refund->refunded_at)
            <p>Refunded Date: {{ $refund->refunded_at->format('d F Y') }}</p>
        @endif
    </div>

    <div class="footer">
        <p>Thank you for your business. For any questions, contact us at info@galaxystore.com</p>
        <p>This is a computer-generated invoice. No signature required.</p>
    </div>
</body>
</html>
