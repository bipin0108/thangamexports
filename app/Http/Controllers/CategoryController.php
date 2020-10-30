<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Category;
use Storage; 
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Category::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('action', function($data){
                        $button = '';
                        $button .= '<a href="'.route("category.edit", $data->category_id).'" class="btn btn-sm btn-primary" ><i class="fas fa-pencil-alt"></i></a>';
                        if($data->category_id !== 2){
                            $button .= '&nbsp;&nbsp;&nbsp;<button type="button" data-id="'.$data->category_id.'" class="btn btn-sm btn-danger delete" ><i class="fas fa-trash-alt"></i></button>';
                        }
                        $button .= '&nbsp;&nbsp;&nbsp;<a href="'.route('sub_category.index', $data->category_id).'" data-id="'.$data->category_id.'" class="btn btn-sm btn-info" ><i class="fas fa-plus"></i> Sub Category</a>';
                        return $button;
                    })->rawColumns(['action', 'is_popular'])->make(true);
        }
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
            'name' => 'required|unique:categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        /*$image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/category');
        $image->move($destinationPath, $input['imagename']);*/
        $imagename = '';
        if(!empty($request->file('image'))){
            /*$img = Image::make($request->file('image'));
            $img->insert(public_path('images/watermark.png'), 'bottom-right', 10, 10);
            $img->save(public_path('images/test.png')); */
            $imagename = $request->file('image')->store('category', 's3');
        }

        $category = new Category;
        $category->name = $request->input('name');
        $category->image = $imagename;
        $category->save(); 

        return redirect()->route('category.index')
                        ->with('success','Category created successfully.');
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
        $category = Category::where('category_id','=',$id)->first();   
        return view('admin.category.edit', compact('category'));
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
            'name' => "required|unique:categories,name,$id,category_id", 
        ]);

        $res = Category::where('category_id',$id)->first();  
        $imagename = $res->image;
        if(!empty($request->file('image'))){


            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            $file = $request->file('image');
            $imagename = $file->store('category', 's3');

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
        
        $affectedRows = Category::where('category_id', $id)
                    ->update(array(
                        'name' => $request->input('name'),
                        'image' => $imagename,
                    ));

        return redirect()->route('category.index')
                            ->with('success','Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $category = Category::where('category_id', $id)->first();
        $imagename = $category->image;
        if(!empty($imagename)){
            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            /*$filename = public_path().'/images/category/'.$file;
            \File::delete($filename);*/
        }
        Category::where('category_id',$id)->delete();
        return redirect()->route('category.index')
                ->with('success','Category deleted successfully.');
    }
}
