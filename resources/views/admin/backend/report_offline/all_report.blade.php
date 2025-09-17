@extends('admin.admin_dashboard')
@section('content')


    <div class="container mx-auto">

        <!-- Orders Table -->
         <br>
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Admin Report Offline</h2>

               <!-- Search Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Search By Date -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-md font-semibold mb-3">By Date</h2>
                <form action="{{ route('admin.offline.search.bydate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Date</label>
                        <input type="date" name="date" class="w-full text-sm px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-1.5 rounded text-sm transition">
                        Search
                    </button>
                </form>
            </div>
            <!-- Search By Month -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-md font-semibold mb-3">By Month</h2>
                <form action="{{ route('admin.offline.search.bymonth') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Month</label>
                        <select name="month" class="w-full text-sm px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option selected>Select Month</option>
                            @foreach (['January','February','March','April','May','June','July','August','September','October','November','December'] as $month)
                                <option value="{{ $month }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Year</label>
                        <select type="year" name="year" class="w-full text-sm px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option selected>Select Year</option>
                            @for ($i = 2022; $i <= 2026; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-1.5 rounded text-sm transition">
                        Search
                    </button>
                </form>
            </div>
            <!-- Search By Year -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-md font-semibold mb-3">By Year</h2>
                <form action="{{ route('admin.offline.search.byyear') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Year</label>
                        <select name="year" class="w-full text-sm px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option selected>Select Year</option>
                            @for ($i = 2022; $i <= 2026; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-1.5 rounded text-sm transition">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
