@extends('admin.admin_dashboard')

@section('content')

<!-- Tambahkan script ini di bagian bawah sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Decoration Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Decoration List</h3>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Gambar</th>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">QTY</th>
                <th class="p-3 text-left">Harga</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product as $key=> $item)
            <tr class="hover:bg-gray-100">
                <td class="p-3 border-b border-gray-200">
                    <img src="{{ asset($item->image) }}" alt="" style="width: 70px; height:40px;">
                </td>
                <td class="p-3 border-b border-gray-200">{{ $item->name }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->qty }}</td>
                <td class="p-3 border-b border-gray-200">{{ number_format($item->price, 2) }}</td>
                <td class="p-3 border-b border-gray-200">
                    @if ($item->status == 1)
                    <span class="text-green-600 font-bold">Active</span>
                    @else
                    <span class="text-red-600 font-bold">Inactive</span>
                    @endif
                </td>
                <td class="p-3 border-b border-gray-200 flex gap-2 items-center">
                    <!-- Tombol Edit -->
                    <a href="{{ route('admin.edit.decoration', $item->id) }}"
                        class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        <i class="fas fa-edit"></i>
                    </a>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('admin.delete.decoration', $item->id) }}" method="POST" class="delete-form">
                        @csrf

                        <button type="button"
                            class="px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 delete-button">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                    <!-- Tombol Toggle Status -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer toggle-class" data-id="{{$item->id}}"
                            {{ $item->status ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-red-500 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500">
                        </div>
                    </label>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('.delete-form');

        Swal.fire({
            title: 'Konfirmasi Hapus Decoration',
            text: "Apakah Anda yakin ingin menghapus decoration ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') ? 1 : 0;
        var product_id = $(this).data('id');

        // Update the status text immediately
        var statusText = $(this).closest('tr').find('td:nth-child(5) span');
        if (status) {
            statusText.removeClass('text-red-600').addClass('text-green-600').text('Active');
        } else {
            statusText.removeClass('text-green-600').addClass('text-red-600').text('Inactive');
        }

        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/admin/changeStatus',
            data: {
                'status': status,
                'product_id': product_id
            },
            success: function(data) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                if ($.isEmptyObject(data.error)) {
                    Toast.fire({
                        type: 'success',
                        title: data.success,
                    });
                } else {
                    Toast.fire({
                        type: 'error',
                        title: data.error,
                    });
                }
            },
            error: function(xhr, status, error) {
                // Revert the toggle if there's an error
                $(this).prop('checked', !status);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mengubah status'
                });
            }
        });
    });
});
</script>

@endsection