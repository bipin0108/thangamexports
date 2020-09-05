<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';

    protected $primaryKey = 'item_id';

    protected $fillable = ['order_id', 'product_id', 'qty', 'note'];

    public function product() {
    	return $this->belongsTo(Product::class,'product_id');
	}
}
