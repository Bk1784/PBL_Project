<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

    public function AdminStoreProduct(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $pcode = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => 5, 'prefix' => 'PC']);
    
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
    
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(300, 300)->save(public_path('upload/product/' . $name_gen));
            $save_url = 'upload/product/' . $name_gen;
    
            Product::create([
                'name' => $request->name,
                'slug' => strtolower(str_replace(' ', '-', $request->name)),
                'code' => $pcode,
                'qty' => $request->qty,
                'price' => $request->price,
                'status' => 1,
                'created_at' => Carbon::now(),
                'image' => $save_url,
            ]);
    
            $notification = [
                'message' => 'Product Inserted Successfully',
                'alert-type' => 'success'
            ];
    
            return redirect()->route('admin.all.product')->with($notification);
        } else {
            return redirect()->back()->with('error', 'Image file is required');
        }
    }
    // End Method 

    public function AdminEditProduct($id){
        $product = Product::find($id);
        return view('admin.backend.product.edit_product', compact('product'));
    }


    public function AdminUpdateProduct(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'qty' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0.01',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'remove_image' => 'nullable|boolean'
    ]);
    $pro_id = $request->id;
    $product = Product::findOrFail($pro_id);
    $data = [
        'name' => $request->name,
        'slug' => strtolower(str_replace(' ', '-', $request->name)),
        'client_id' => $request->client_id,
        'qty' => $request->qty,
        'price' => $request->price,
        'updated_at' => now()
    ];
    // Handle image removal
    if ($request->has('remove_image') && $request->remove_image) {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        $data['image'] = null;
    }
    // Handle new image upload
    if ($request->file('image')) {
        // Delete old image if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        $image = $request->file('image');
        $manager = new ImageManager(new Driver());
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        $img = $manager->read($image);
        $img->resize(300, 300)->save(public_path('upload/product/'.$name_gen));
        $data['image'] = 'upload/product/'.$name_gen;
    }
    // Update product
    $product->update($data);
    $notification = [
        'message' => 'Product Updated Successfully',
        'alert-type' => 'success'
    ];
    return redirect()->route('admin.all.product')->with($notification);
}
    // End Method 

    public function AdminDeleteProduct($id){
        $item = Product::find($id);
        $img = $item->image;
        unlink($img);
        Product::find($id)->delete();
        $notification = array(
            'message' => 'Product Delete Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    // End Method 

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
     // End Method 

}
