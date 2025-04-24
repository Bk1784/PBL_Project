<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice</title>

  <style type="text/css">
    * {
      font-family: Verdana, Arial, sans-serif;
    }
    table {
      font-size: x-small;
    }
    tfoot tr td {
      font-weight: bold;
      font-size: x-small;
    }
    .gray {
      background-color: lightgray
    }
    .font {
      font-size: 15px;
    }
    .authority {
      float: right
    }
    .authority h5 {
      margin-top: -10px;
      color: #4F46E5; 
      margin-left: 35px;
    }
    .thanks p {
      color: #4F46E5;
      font-size: 16px;
      font-weight: normal;
      font-family: serif;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <table width="100%" style="background: #F7F7F7; padding:0 20px;">
    <tr>
      <td valign="top">
        <h2 style="color: #4F46E5; font-size: 26px;"><strong>Galaxy Store</strong></h2>
      </td>
      <td align="right">
        <pre class="font">
            Email: @galaxystore.com
            Phone: +62 812-3456-7890
            Adress: Srono, Banyuwangi
        </pre>
      </td>
    </tr>
  </table>

  <table width="100%" style="background:white; padding:2px;"></table>

  <table width="100%" style="background: #F7F7F7; padding: 5px;" class="font">
  <tr>
    <td>
      <p class="font" style="margin-left: 20px;">
        <strong>Name:</strong> {{ $order->user->name }}<br>
        <strong>Email:</strong> {{ $order->user->email }}<br>
        <strong>Phone:</strong> {{ $order->user->phone }}<br>
        <strong>Address:</strong> {{ $order->user->address }}<br>
        <strong>Invoice:</strong> #{{ $order->invoice_no }}<br>
        <strong>Order Date:</strong> {{ $order->created_at }}<br>
        <strong>Payment Method:</strong> {{ $order->payment_method }}
      </p>
    </td>
  </tr>
</table>


  <br>
  <h3 style="color: #4F46E5;">Products</h3>

  <table width="100%">
    <thead style="background-color: #4F46E5; color: #FFFFFF;">
      <tr class="font">
        <th>Image</th>
        <th>Product Name</th>
        <th>Code</th>
        <th>Quantity</th>
        <th>Harga</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orderItem as $item)
      <tr class="font">
        <td align="center">
          <img src="{{ public_path($item->product->image) }}" height="60px" width="60px" alt="">
        </td>
        <td align="center">{{ $item->product->name }}</td>
        <td align="center">{{ $item->product->code }}</td>
        <td align="center">{{ $item->qty }}</td>
        <td align="center">{{ $item->price }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <br>
  <table width="100%" style="padding: 0 10px;">
    <tr>
      <td align="right">
        <h2><span style="color: #4F46E5;">Subtotal:</span>{{ $Price }}</h2>
        <h2><span style="color: #4F46E5;">Ongkos Kirim:</span>{{ $shippingFee }}</h2>
        <h2><span style="color: #4F46E5;">Total:</span> {{ $totalAmount }}</h2>
      </td>
    </tr>
  </table>

  <div class="thanks mt-3">
    <p>Thank you for shopping at Galaxy Store!</p>
  </div>

  <div class="authority float-right mt-5">
    <p>-----------------------------------</p>
    <h5>Authorized Signature:</h5>
  </div>

</body>
</html>
