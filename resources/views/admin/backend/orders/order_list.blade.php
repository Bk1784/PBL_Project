<h1>Daftar Order</h1>
@foreach($orders as $order)
<p>Invoice: {{ $order->invoice_no }} | Status: {{ $order->status }}</p>
@endforeach