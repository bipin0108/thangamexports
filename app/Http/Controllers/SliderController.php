<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slider;
use DataTables;
use Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Slider::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('action', function($data){
                        $button = '<a href="'.route('slider.edit', $data->slider_id).'" type="button" class="btn btn-sm btn-primary" ><i class="fas fa-pencil-alt"></i></a>';
                        $button .= '&nbsp;&nbsp;&nbsp;<button type="button" id="'.$data->slider_id.'" class="btn btn-sm btn-danger delete" ><i class="fas fa-trash-alt"></i></button>';
                        return $button;
                    })->rawColumns(['action'])->make(true);
        }
        return view('admin.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.slider.create');
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
 
        $imagename = '';
        if(!empty($request->file('image'))){
            $file = $request->file('image');
            $imagename = $file->store('slider', 's3');
        }

        $slider = new Slider;
        $slider->image = $imagename;
        $slider->save();

        return redirect()->route('slider.index')
                        ->with('success','slider created successfully.');
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
        $slider = Slider::where('slider_id','=',$id)->first();
        return view('admin.slider.edit',compact('slider'));
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
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $slider = slider::where('slider_id',$id)->first();  
        $imagename = $slider->image;
        if(!empty($request->file('image'))){

            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }
            $file = $request->file('image');
            $imagename = $file->store('slider', 's3');
        }
        
        $affectedRows = Slider::where('slider_id', $id)
                    ->update(array(  
                        'image' => $imagename, 
                    ));

        return redirect()->route('slider.index')
                            ->with('success','slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sample_data  $sample_data
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = slider::where('slider_id', $id)->first();
        $imagename = $slider->image;
        if(!empty($imagename)){
            if(Storage::disk('s3')->exists($imagename)) {
                Storage::disk('s3')->delete($imagename);
            }  
            /*$filename = public_path().'/images/slider/'.$file;
            \File::delete($filename);*/
        }
        slider::where('slider_id',$id)->delete();
        return redirect()->route('slider.index')
                ->with('success','Slider deleted successfully.');
    }
}
