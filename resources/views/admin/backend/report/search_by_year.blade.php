@extends('client.client_dashboard')
@section('client')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">All Search By Year Order</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                           
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                     
                    <div class="card-body">
        <h3 class="text-danger">Search By Year {{ $year }}</h3>
        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
            <thead>
            <tr>
                <th>Sl</th>
                <th>Date</th>
                <th>Invoice</th>
                <th>Amount</th>
                <th>Payment</th> 
                <th>Status</th>
                <th>Action </th> 
            </tr>
            </thead>

        <tbody>
            @php $key = 1; @endphp
            @foreach ($orderItemGroupData as $orderId => $items)
                @php $firstItem = $items->first(); @endphp
                @if ($firstItem && $firstItem->order)
                <tr>
                    <td>{{ $key++ }}</td>
                    <td>{{ $firstItem->order->order_date }}</td>
                    <td>{{ $firstItem->order->invoice_no }}</td>
                    <td>{{ $firstItem->order->amount }}</td>
                    <td>{{ $firstItem->order->payment_method }}</td>
                    <td><span class="badge bg-primary">{{ $firstItem->order->status }}</span></td>
                    <td>
                        <a href="{{ route('client.order.details', $firstItem->order_id) }}" class="btn btn-info waves-effect waves-light">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>

        
        </table>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row --> 

         
    </div> <!-- container-fluid -->
</div>

@endsection