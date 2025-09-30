@extends('admin.admin_dashboard')

@section('content')
    <!--  -->
    <!-- Tambahkan script ini di bagian bawah sebelum </body> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Customer Table -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold mb-4">Produk Masuk</h3>
            <div class="flex justify between items-center gap-4">
            <form method="GET" action="{{ route('admin.backend.purchases.all') }}" class="flex items-center gap-2">
                <select name="month" class="border border-gray-300 p-2 rounded mb-4" onchange="this.form.submit()">
                    @foreach ($month as $m)
                        <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>{{ $m }}
                        </option>
                    @endforeach
                </select>
            </form>
            <form method="GET" action="{{ route('admin.backend.purchases.exportPdf') }}">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Cetak PDF</button>
            </form>
        </div>
        </div>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Nama Barang</th>
                    <th class="p-3 text-left">QTY</th>
                    <th class="p-3 text-left">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @if($purchases->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada laporan produk masuk</td>
                    </tr>
                @else
                    @foreach ($purchases as $key => $purchase)
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 border-b border-gray-200">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 border-b border-gray-200">{{ $purchase->product->name }}</td>
                            <td class="p-3 border-b border-gray-200">{{ $purchase->qty }}</td>
                            <td class="p-3 border-b border-gray-200">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
