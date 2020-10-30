<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Product;
use App\Category;
use App\SubCategory;
use Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Product::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('is_popular', function($data){
                        if($data->is_popular === 1){
                            $toggle = '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input is_popular" data-product_id="'.$data->product_id.'" data-is_popular="0" id="customSwitch'.$data->product_id.'" checked>
                                <label class="custom-control-label" for="customSwitch'.$data->product_id.'"></label>
                            </div>';
                        }else{
                            $toggle = '<div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input is_popular" data-product_id="'.$data->product_id.'" data-is_popular="1"  id="customSwitch'.$data->product_id.'">
                                <label class="custom-control-label" for="customSwitch'.$data->product_id.'"></label>
                            </div>';
                        }
                        return $toggle;
                    })->addColumn('action', function($data){
                        $button = '<a href="'.route("product.edit", $data->product_id).'" class="btn btn-sm btn-primary" ><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" data-id="'.$data->product_id.'" class="btn btn-sm btn-danger delete" ><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })->rawColumns(['action', 'is_popular'])->make(true);
        }
        return view('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::get();
        return view('admin.product.create', compact('category'));
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
            'category_id' => 'required|numeric|not_in:0',
            'sub_category_id' => 'required|numeric|not_in:0',
            'product_code' => 'required|unique:products,product_code', 
            'weight' => 'required|numeric|not_in:0',
            'stone' => 'required',   
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        /*$image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/product');
        $image->move($destinationPath, $imagename);*/
        $imagename = '';
        if(!empty($request->file('image'))){
            $file = $request->file('image');
            $imagename = $file->store('product', 's3');
        }

        $product = new Product;
        $product->category_id = $request->input('category_id');
        $product->sub_category_id = $request->input('sub_category_id');
        $product->product_code = $request->input('product_code'); 
        $product->weight = $request->input('weight');
        $product->stone = $request->input('stone');
        $product->kt = !empty($request->input('kt'))?implode(',', $request->input('kt')):'';
        $product->image = $imagename;
        $product->save(); 

        return redirect()->route('product.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::get();      
        $product = Product::where('product_id','=',$id)->first();
        return view('admin.product.edit',compact('category', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric|not_in:0',
            'sub_category_id' => 'required|numeric|not_in:0',
            'product_code' => "required|unique:products,product_code,$id,product_id", 
            'weight' => 'required|string|numeric|not_in:0',
            'stone' => 'required', 
        ]);

        $res = Product::where('product_id',$id)->first();  
        $imagename = $res->image;
        if(!empty($request->file('image'))){

            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            $file = $request->file('image');
            $imagename = $file->store('product', 's3');

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
                'sub_category_id' => $request->input('sub_category_id'),
                'product_code' => $request->input('product_code'), 
                'weight' => $request->input('weight'),
                'stone' => $request->input('stone'),
                'kt' => (!empty($request->input('kt'))?implode(',', $request->input('kt')):''),
                'image' => $imagename,
            ));

        return redirect()->route('product.index')
                            ->with('success','Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('product_id', $id)->first();
        $imagename = $product->image;
        if(!empty($imagename)){
            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }  
            /*$filename = public_path().'/images/product/'.$file;
            \File::delete($filename);*/
        }
        Product::where('product_id',$id)->delete();
        return redirect()->route('product.index')
                ->with('success','Product deleted successfully.');
    }


    public function is_popular(Request $request)
    {
        $affectedRows = Product::where('product_id', $request->input('product_id'))
            ->update(array(
                'is_popular' => $request->input('is_popular'),
            ));

        return response()->json([
            'status'=>true,
            'message'=>'Product is popular successfully.' 
        ],200);
    }


    public function sub_category($id)
    {
        $categories = SubCategory::where('category_id', $id)->get();           
        $return="<option value=''>Select Sub Category</option>";
        foreach($categories as $category){
            $return.="<option value=".$category->sub_category_id.">".$category->name."</option>";
        }                                
        echo   $return;       
    }
}
