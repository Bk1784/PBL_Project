

@extends('admin.admin_dashboard')

@section('content')
<!-- Customer Table -->
<div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold mb-4">Customer List</h3>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 border-b border-gray-200">John Doe</td>
                            <td class="p-3 border-b border-gray-200">john@example.com</td>
                            <td class="p-3 border-b border-gray-200 text-green-500 font-bold">Aktif</td>
                            <td class="p-3 border-b border-gray-200">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition duration-300">Hapus</button>
                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition duration-300 ml-2">Edit</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 border-b border-gray-200">Jane Smith</td>
                            <td class="p-3 border-b border-gray-200">jane@example.com</td>
                            <td class="p-3 border-b border-gray-200 text-red-500 font-bold">Tidak Aktif</td>
                            <td class="p-3 border-b border-gray-200">
                                <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition duration-300">Hapus</button>
                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition duration-300 ml-2">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection
