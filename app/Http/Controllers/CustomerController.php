<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\User;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 
 class CustomerController extends Controller
 {
     public function Index(){
         return view('customer.master');
     }

     public function ProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
    
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address; 
    
        $oldPhotoPath = $data->photo;
    
        if ($request->hasFile('photo')) {
           $file = $request->file('photo');
           $filename = time().'.'.$file->getClientOriginalExtension();
           $file->move(public_path('upload/user_images'),$filename);
           $data->photo = $filename;
    
           if ($oldPhotoPath && $oldPhotoPath !== $filename) {
             $this->deleteOldImage($oldPhotoPath);
           }
        }
        
        $data->save();
    
        $notification = array(
            'message' => 'Profile Updated Successfully',
            'alert-type' => 'success'
        );
    
        return redirect()->route('customer.profile')->with($notification);
    }
     // End Method 

     private function deleteOldImage(string $oldPhotoPath): void {
        $fullPath = public_path('upload/user_images/'.$oldPhotoPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
     }
     // End Private Method 
     public function Atk(){
         return view('customer.atk_dashboard');
     }
 
     public function CustomerLogout(){
         Auth::guard('web')->logout();
         return redirect()->route('login')->with('success', 'Logout Success');
     }

     public function CustomerProfile()
     {
         $customer = Auth::guard('customer')->user();
         return view('customer.profile', compact('customer'));
     }

     public function CustomerEditProfile()
     {
         $customer = Auth::guard('customer')->user();
         return view('customer.edit_profile', compact('customer'));
     }

     
 }