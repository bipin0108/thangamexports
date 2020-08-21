<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource; 

class ApiController extends Controller
{


	public function category_list(){
		// Get the posts
		$category = Category::get();

		// Return collection of posts as a resource
		return CategoryResource::collection($category);
	}

    public function product_list(Request $request){ 
    	// Get the posts
        $product = Product::where('category_id',$request->query('category_id')) 
        			->whereRaw('FIND_IN_SET(?,kt)', $request->query('kt'))
        			->paginate(10);
        			 
  
        // Return collection of posts as a resource
        return ProductResource::collection($product);
    }

}
