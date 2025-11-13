<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class ManageController extends Controller
{
    public function AdminAllProduct(){
       $product = Product::orderBy('id', 'desc')->get();
       return view('admin.backend.product.all_product', compact('product'));
        
    }

    public function ChangeStatus(Request $request){
        $product = Product::find($request->product_id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status Change Successfully']);
    }

    public function AdminAddProduct(){
        $client = Client::latest()->get();
        return view('admin.backend.product.add_product', compact('client'));
    }

    public function AdminStoreProduct(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:65535',
        'qty' => 'required|integer|min:1',
        'price' => 'required|numeric|min:1',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $pcode = IdGenerator::generate([
        'table' => 'products',
        'field' => 'code',
        'length' => 5,
        'prefix' => 'PC'
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

        // 📁 Lokasi folder tujuan
        $uploadPath = public_path('upload/product');

        // 🔧 Buat folder jika belum ada
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // 🚀 Pindahkan file ke folder public/upload/product
        $image->move($uploadPath, $name_gen);

        // 💾 Simpan path relatif ke database
        $save_url = 'upload/product/' . $name_gen;

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'code' => $pcode,
            'qty' => $request->qty,
            'price' => $request->price,
            'status' => 1,
            'image' => $save_url,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.all.product')->with([
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        ]);
    }

    return redirect()->back()->with('error', 'Image file is required');
}  
    public function AdminEditProduct($id){
        $product = Product::find($id);
        return view('admin.backend.product.edit_product', compact('product'));
    }

    public function AdminUpdateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_image' => 'nullable|boolean'
        ]);

        $pro_id = $request->id;
        $product = Product::findOrFail($pro_id);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'slug' => strtolower(str_replace(' ', '-', $request->name)),
            'client_id' => $request->client_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'updated_at' => now()
        ];

        // ✅ Hapus gambar lama kalau checkbox remove_image dicentang
        if ($request->has('remove_image') && $request->remove_image) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $data['image'] = null;
        }

        // ✅ Upload gambar baru
        if ($request->file('image')) {
            // Hapus gambar lama kalau ada
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = 'upload/product/' . $name_gen;

            // Simpan langsung ke folder public/upload/product
            $image->move(public_path('upload/product'), $name_gen);

            // Simpan path relatif ke database
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()->route('admin.all.product')->with([
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        ]);
    }


    public function AdminDeleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // ✅ Hapus file image dari folder public kalau ada
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // ✅ Hapus data product dari database
        $product->delete();

        return redirect()->back()->with([
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }


    public function PendingToko() {
        $client = Client::where('status', 0)->get(); // atau Client::get()
        return view('admin.backend.toko.pending_toko', compact('client'));
    }

    public function ApproveToko(){
        $client = Client::where('status',1)->get();
        return view('admin.backend.toko.approve_toko',compact('client'));
        
    }
   
    public function ClientChangeStatus(Request $request){
        $client = Client::find($request->client_id);
        $client->status = $request->status;
        $client->save();
        return response()->json(['success' => 'Status Change Successfully']);
    }

}
