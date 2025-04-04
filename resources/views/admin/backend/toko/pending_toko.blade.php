@extends('admin.admin_dashboard')

@section('content')

<!-- Tambahkan script ini di bagian bawah sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Customer Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-2xl font-bold mb-4">Produk List</h3>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">S1</th>
                <th class="p-3 text-left">Gambar</th>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Phone</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($client as $key=> $item)
            <tr class="hover:bg-gray-100">
                <td class="p-3 border-b border-gray-200">{{ $key+1 }}</td>
                <td class="p-3 border-b border-gray-200">
                <img src="{{ (!empty($item->photo)) ? asset('storage/'.$item->photo) : asset('upload/no_image.jpg') }}" alt="" style="width: 70px; height:40px;">
                </td>
                <td class="p-3 border-b border-gray-200">{{ $item->name }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->email }}</td>
                <td class="p-3 border-b border-gray-200">{{ $item->phone }}</td>
                <td class="p-3 border-b border-gray-200">
                    <span class="status-text font-bold {{ $item->status ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item->status ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="p-3 border-b border-gray-200 flex gap-2 items-center">
                    <!-- Tombol Toggle Status -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer toggle-class" 
                               data-id="{{$item->id}}" 
                               {{ $item->status ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-red-500 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0; 
            var client_id = $(this).data('id'); 
            var $row = $(this).closest('tr');
            var $statusText = $row.find('.status-text');
            
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/clientchangeStatus',
                data: {'status': status, 'client_id': client_id},
                success: function(data){
                    // Update status text and color
                    if(status == 1) {
                        $statusText.text('Active').removeClass('text-red-600').addClass('text-green-600');
                    } else {
                        $statusText.text('Inactive').removeClass('text-green-600').addClass('text-red-600');
                    }
                    
                    // Show notification
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
                }
            });
        });
    });
</script>

@endsection