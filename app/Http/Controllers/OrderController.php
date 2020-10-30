<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Orders;
use App\OrderItems;
use DataTables;
use PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
    	if($request->ajax()){
            $data = Orders::select('orders.order_id','orders.order_no','orders.status',\DB::raw("CONCAT(users.first_name,' ',users.last_name) AS username"),'users.mobile','users.email')->
            join('users', 'orders.user_id', '=', 'users.id')->get();
            return DataTables::of($data)
                    ->addIndexColumn() 
                    ->addColumn('status', function($item){
                        $select = '<select name="status" class="form-control status">';
                        $select .= "<option data-order_id='".$item->order_id."'  ".(($item->status == 'Pending')?'selected':'')." value='Pending'>Pending</option>";
                        $select .= "<option data-order_id='".$item->order_id."' ".(($item->status == 'Confirmed')?'selected':'')." value='Confirmed'>Confirmed</option>";
                        $select .= "<option data-order_id='".$item->order_id."' ".(($item->status == 'Cancelled')?'selected':'')." value='Cancelled'>Cancelled</option>";
                        $select .= '</select>';
                        return $select; 
                    })->addColumn('action', function($item){
                        $button = '<a href="'.route('order.detail', $item->order_id).'" type="button" data-id="'.$item->order_id.'" class="btn btn-sm btn-primary" ><i class="fas fa-eye"></i></a>';
                        $button .= ' <a href="'.route('order.pdf', $item->order_id).'" target="_blank" class="btn btn-sm btn-primary" ><i class="far fa-file-pdf"></i></a>';
                        return $button;
                    })->rawColumns(['action', 'status'])->make(true);
        }
        return view('admin.order.index');
    }

    public function order_details(Request $request, $id)
    {
        if($request->ajax()){ 
            $data = OrderItems::select('order_items.qty','order_items.note','products.product_code','products.weight','products.stone','products.image')
            ->where('order_items.order_id', $id)
            ->join('products', 'order_items.product_id', '=', 'products.product_id')->get(); 
            return DataTables::of($data)->addIndexColumn()->make(true);
        }
        $order = Orders::where('orders.order_id', $id)->first();
        return view('admin.order.detail', compact('order'));
    }

    public function status_change(Request $request){
        $affectedRows = Orders::where('order_id', $request->input('order_id'))
            ->update(array(
                'status' => $request->input('status'),
            ));

        return response()->json([
            'status'=>true,
            'message'=>'Order status has been changed successfully.' 
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
