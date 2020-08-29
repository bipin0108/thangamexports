<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use App\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = User::where('is_admin','0')->latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('action', function($data){
                        $button = '<a href="'.route('user.edit', $data->id).'" type="button" class="btn btn-sm btn-primary" ><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" id="'.$data->id.'" class="btn btn-sm btn-danger delete" ><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })->rawColumns(['action'])->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email', 
            'password' => 'required|string|min:6',
            'mobile' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /*$image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/user');
        $image->move($destinationPath, $imagename);*/

        $file = $request->file('image');
        $filePath = $file->store('user', 's3');

        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name'); 
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->mobile = $request->input('mobile');
        $user->profile_image = $imagename;
        $user->save(); 

        event(new UserRegisteredEvent($user));

        return redirect()->route('sample.index')
                        ->with('success','User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id','=',$id)->first();
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric|not_in:0',
            'product_code' => "required|unique:products,product_code,$id,product_id", 
            'weight' => 'required|string|numeric|not_in:0',
            'stone' => 'required|string|numeric|not_in:0',
            'kt' => 'required',
        ]);

        $res = User::where('product_id',$id)->first();  
        $imagename = $res->image;
        if(!empty($request->file('image'))){

            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            $file = $request->file('image');
            $imagename = $file->store('user', 's3');
            
            /*$file = $res->image;
            if(!empty($file)){
                $filename = public_path().'/images/category/'.$file;
                \File::delete($filename);
            }
            $image = $request->file('image');
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/category');
            $image->move($destinationPath, $imagename);*/
        }
        
        $affectedRows = Product::where('product_id', $id)
                    ->update(array(
                        'category_id' => $request->input('category_id'),
                        'product_code' => $request->input('product_code'), 
                        'weight' => $request->input('weight'),
                        'stone' => $request->input('stone'),
                        'kt' => implode(',', $request->input('kt')),
                        'image' => $imagename,
                    ));

        return redirect()->route('product.index')
                            ->with('success','Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
