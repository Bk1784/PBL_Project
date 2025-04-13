<h1>Detail Order Admin</h1>
<p>Invoice No: {{ $order->invoice_no }}</p>
<p>Nama Customer: {{ $order->name }}</p>

<h3>Items:</h3>
@foreach($order->orderItems as $item)
<p>{{ $item->product->name }} - Qty: {{ $item->qty }} - Harga: {{ $item->price }}</p>
@endforeach