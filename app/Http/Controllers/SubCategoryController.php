<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Category;
use App\SubCategory;
use Storage; 

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        if($request->ajax()){
            $data = SubCategory::where('sub_categories.category_id', '=', $id)->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('action', function($data){
                        $button = '<a href="'.route("sub_category.edit", ['cat_id'=>$data->category_id,'sub_cat_id'=>$data->sub_category_id]).'" class="btn btn-sm btn-primary" ><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" data-cat_id="'.$data->category_id.'" data-sub_cat_id="'.$data->sub_category_id.'" class="btn btn-sm btn-danger delete" ><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })->rawColumns(['action', 'is_popular'])->make(true);
        }
        $category = Category::where('categories.category_id', '=', $id)->first();
        return view('admin.sub_category.index', compact('category', 'id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('admin.sub_category.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        /*$image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images/category');
        $image->move($destinationPath, $input['imagename']);*/
        $imagename = '';
        if(!empty($request->file('image'))){
            $file = $request->file('image');
            $imagename = $file->store('sub_category', 's3');
        }

        $category = new SubCategory;
        $category->category_id = $id;
        $category->name = $request->input('name');
        $category->image = $imagename;
        $category->save(); 

        return redirect()->route('sub_category.index', $id)
                        ->with('success','Sub Category created successfully.');
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
    public function edit($cat_id, $sub_cat_id)
    {
        $sub_category = SubCategory::where('sub_category_id','=',$sub_cat_id)->first();   
        return view('admin.sub_category.edit', compact('sub_category', 'cat_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cat_id, $sub_cat_id)
    {
        $this->validate($request, [
            'name' => "required|string", 
        ]);

        $res = SubCategory::where('sub_category_id',$sub_cat_id)->first();  
        $imagename = $res->image;
        if(!empty($request->file('image'))){


            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            $file = $request->file('image');
            $imagename = $file->store('sub_category', 's3');

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
        
        $affectedRows = SubCategory::where('sub_category_id', $sub_cat_id)
                    ->update(array(
                        'name' => $request->input('name'),
                        'image' => $imagename,
                    ));

        return redirect()->route('sub_category.index', $cat_id)
                            ->with('success','Sub Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cat_id, $sub_cat_id)
    {
        
        $category = SubCategory::where('sub_category_id', $sub_cat_id)->first();
        $imagename = $category->image;
        if(!empty($imagename)){
            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            /*$filename = public_path().'/images/category/'.$file;
            \File::delete($filename);*/
        }
        SubCategory::where('sub_category_id',$sub_cat_id)->delete();
        return redirect()->route('sub_category.index', $cat_id)
                ->with('success','Sub Category deleted successfully.');
    }
}
