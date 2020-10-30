<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\SubCategory as SubCategoryResource;
use App\Http\Resources\Product as ProductResource; 
use App\Http\Resources\Slider as SliderResource; 
use App\Http\Resources\Orders as OrdersResource; 
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Product;
use App\Category;
use App\SubCategory;
use App\User;
use App\Slider;
use App\Orders;
use App\OrderItems;
use PDF;

class ApiController extends Controller{

    public function __construct() {
        // $this->middleware('auth:api', ['except' => ['login', 'category_list', 'product_list', 'dashboard']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'An error occurred, please try again later.',
                'error'=>$validator->errors()
            ], 422);
        }

        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json([
                'status'=>false,
                'message' => 'Wrong email or password.',
                'error' => ''
            ], 401);
        }

        return response()->json([
            'status'=>true,
            'message'=>'Login successfully.', 
            'data' => $this->createNewToken($token)
        ],200);
    }

    public function refresh() {
        return response()->json([
            'status'=>true,
            'message'=>'Success.', 
            'data' => $this->createNewToken(auth('api')->refresh())
        ],200); 
    }

    protected function createNewToken($token){
        $user = auth('api')->user(); 
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 2,
            'user' => $user
        ];
    }
    
	public function category_list(){

		// Get the posts
		$category = Category::get();

        return response()->json([
            'status'=>true,
            'message'=>'Success.', 
            'data' => CategoryResource::collection($category)
        ],200);
	}

    public function sub_category_list($id){

        // Get the posts
        $sub_category = SubCategory::where('category_id',$id)->get();

        return response()->json([
            'status'=>true,
            'message'=>'Success.', 
            'data' => SubCategoryResource::collection($sub_category)
        ],200);
    }

    public function product_list(Request $request){ 
    	// Get the posts
        $product = Product::where('sub_category_id',$request->query('sub_category_id'));
                if(!empty($_GET['kt'])){
        			$product->whereRaw('FIND_IN_SET(?,kt)', $request->query('kt'));
                }
    			$result = $product->paginate(10);
        			 
        // Return collection of posts as a resource
        return ProductResource::collection($result);
    }

    public function dashboard(){
        // Get products
        $slider = Slider::all();

        // Get products
        $category = Category::all();

        // Get products
        $product = Product::where('is_popular',1)->get();

        return response()->json([
            'status'=>true,
            'message'=>'Success.', 
            'slider' => SliderResource::collection($slider),
            'category' => CategoryResource::collection($category),
            'products' => ProductResource::collection($product)
        ],200);
    }

    public function order_list($id)
    {
        // Get the posts
        $order = Orders::where('user_id',$id)->get();
        
        return response()->json([
            'status'=>true,
            'message'=>'Success.', 
            'data' => OrdersResource::collection($order)
        ],200);
    }

    public function order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'products' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>'An error occurred, please try again later.',
                'error'=>$validator->errors()
            ], 422);
        }

        $user_id = $request->input('user_id');
        $products = json_decode($request->input('products'), true); 

        $order = new Orders();
        $order->user_id = $user_id;
        $latestOrder = Orders::orderBy('created_at','DESC')->first();
        if(!empty($latestOrder)){
            $order->order_no = 'OTE/20-21/'.str_pad($latestOrder->order_id + 1, 5, "0", STR_PAD_LEFT);
        }else{
            $order->order_no = 'OTE/20-21/'.str_pad(1, 5, "0", STR_PAD_LEFT);
        }
        $order->save();
        $order_id = $order->order_id;

        foreach ($products as $idx => $product){
            $orderItem = new OrderItems();
            $orderItem->order_id = $order_id;
            $orderItem->product_id = $product['product_id'];
            $orderItem->qty = $product['qty'];
            $orderItem->note = $product['note'];
            $orderItem->save();
        }

        return response()->json([
            'status'=>true,
            'message'=>'Order successfully.' 
        ],200);
    }

    public function order_pdf($id){
        
        $data = array();
        $order = Orders::select('orders.order_id', 'orders.order_no', \DB::raw("SUM(order_items.qty) AS qty"), \DB::raw("SUM(order_items.qty*products.weight) AS weight"))
                        ->where('orders.order_id', $id)
                        ->join('order_items', 'orders.order_id', '=', 'order_items.order_id')
                        ->join('products', 'order_items.product_id', '=', 'products.product_id')->first(); 
        $data['order'] = $order;
        $order_items = OrderItems::select('order_items.qty','order_items.note','products.product_code','products.weight','products.stone','products.image')
            ->where('order_items.order_id', $id)
            ->join('products', 'order_items.product_id', '=', 'products.product_id')->get(); 
        $data['order_items'] = $order_items; 
        // return view('admin.order.pdf', compact('order', 'order_items'));
        $pdf = \App::make('dompdf.wrapper');        
        $pdf->loadView('admin.order.pdf', compact('order', 'order_items'));
        return $pdf->stream('sample.pdf');
    }

}
