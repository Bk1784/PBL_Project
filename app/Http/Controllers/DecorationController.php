<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decoration;
use Illuminate\Support\Facades\File;

class DecorationController extends Controller
{
    // List semua dekorasi
    public function index()
    {
        $product = Decoration::latest()->get();
        return view('backend.decoration.all_decoration', compact('product'));
    }

    // Form tambah dekorasi
    public function create()
    {
        return view('backend.decoration.add_decoration');
    }

    // Simpan dekorasi baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'description' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/decoration'), $filename);
            $imagePath = 'upload/decoration/' . $filename;
        }

        Decoration::create([
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => 1,
        ]);

        return redirect()->route('admin.all.decoration')->with('success', 'Decoration berhasil ditambahkan');
    }

    // Edit dekorasi
    public function edit($id)
    {
        $product = Decoration::findOrFail($id);
        return view('backend.decoration.edit_decoration', compact('product'));
    }

    // Update dekorasi
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'description' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        ]);

        $decoration = Decoration::findOrFail($id);

        // Hapus gambar lama jika dicentang remove_image
        if ($request->has('remove_image') && $decoration->image) {
            File::delete(public_path($decoration->image));
            $decoration->image = null;
        }

        // Upload gambar baru
        if ($request->file('image')) {
            if ($decoration->image && File::exists(public_path($decoration->image))) {
                File::delete(public_path($decoration->image));
            }
            $file = $request->file('image');
            $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/decoration'), $filename);
            $decoration->image = 'upload/decoration/' . $filename;
        }

        // Update data
        $decoration->update([
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $decoration->image,
        ]);

        return redirect()->route('admin.all.decoration')->with('success', 'Decoration berhasil diupdate');
    }


    // Hapus dekorasi
    public function destroy($id)
    {
        $product = Decoration::findOrFail($id);
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }
        $product->delete();
        return redirect()->back()->with('success', 'Decoration berhasil dihapus');
    }

    // Toggle status aktif/inaktif
    public function changeStatus(Request $request)
    {
        $product = Decoration::find($request->product_id);
        if ($product) {
            $product->status = $request->status;
            $product->save();
            return response()->json(['success' => 'Status updated successfully']);
        }
        return response()->json(['error' => 'Decoration not found']);
    }
}
