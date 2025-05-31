@extends('client.client_dashboard')
@section('client')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">All Search By Month Order</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0"></ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Form Pencarian Bulan dan Tahun -->
                        <form method="GET" action="{{ route('client.search.by.month') }}" class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="month" class="form-label">Select Month</label>
                                <select name="month" id="month" class="form-select" required>
                                    <option value="" selected disabled>Choose Month</option>
                                    @foreach ([
                                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                    ] as $key => $monthName)
                                        <option value="{{ $key }}">{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="year_name" class="form-label">Select Year</label>
                                <select name="year_name" id="year_name" class="form-select" required>
                                    <option value="" selected disabled>Choose Year</option>
                                    @for ($i = date('Y'); $i >= 2000; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>
                        <!-- End Form -->

                        <!-- Hasil Pencarian -->
                        <h3 class="text-danger">Search By Month: {{ $month }} and Year {{ $year }}</h3> <!-- gunakan $year, bukan $years -->

                    <tbody>
                        @php $key = 1; @endphp
                        @foreach ($orderItemGroupData as $orderGroup)
                            @foreach ($orderGroup as $item)
                                <tr>
                                    <td>{{ $key++ }}</td> <!-- pakai $key++ agar bertambah -->
                                    <td>{{ $item->order->order_date }}</td>
                                    <td>{{ $item->order->invoice_no }}</td>
                                    <td>{{ $item->order->amount }}</td>
                                    <td>{{ $item->order->payment_method }}</td>
                                    <td><span class="badge bg-primary">{{ $item->order->status }}</span></td>
                                    <td>
                                        <a href="{{ route('client.order.details',$item->order_id) }}" class="btn btn-info waves-effect waves-light">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @break
                            @endforeach
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
