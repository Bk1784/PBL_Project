@extends('admin.admin_dashboard') {{-- sesuaikan dengan layout admin kamu --}}

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-6">Manajemen Rating & Review</h3>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-yellow-100 rounded-lg text-center">
            <p class="text-lg font-semibold">Rata-rata Rating</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $avgRating ?? 0 }} ⭐</p>
        </div>
        <div class="p-4 bg-blue-100 rounded-lg text-center">
            <p class="text-lg font-semibold">Total Review</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalReviews }}</p>
        </div>
        <div class="p-4 bg-green-100 rounded-lg text-center">
            <p class="text-lg font-semibold">Distribusi Rating</p>
            @for ($i = 5; $i >= 1; $i--)
                <p>{{ $i }} ⭐ : {{ $distribution[$i] ?? 0 }}</p>
            @endfor
        </div>
    </div>

    <!-- Filter & Sort -->
    <form method="GET" class="flex space-x-4 mb-6">
        <div>
            <label class="block text-gray-700">Filter Rating</label>
            <select name="rating" class="border rounded p-2">
                <option value="">Semua</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                        {{ $i }} ⭐
                    </option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-gray-700">Urutkan</label>
            <select name="sort" class="border rounded p-2">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Terapkan</button>
        </div>
    </form>

    <!-- Daftar Review -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Rating</th>
                    <th class="p-3 text-left">Komentar</th>
                    <th class="p-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ratings as $rating)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b">{{ $rating->invoice_no }}</td>
                    <td class="p-3 border-b">{{ $rating->customer_name }}</td>
                    <td class="p-3 border-b">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star {{ $i <= $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                        @endfor
                    </td>
                    <td class="p-3 border-b">{{ $rating->comment ?? '-' }}</td>
                    <td class="p-3 border-b">{{ \Carbon\Carbon::parse($rating->created_at)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada review</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ratings->links() }}
    </div>
</div>
@endsection
