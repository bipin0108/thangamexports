<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = ['product_code', 'price', 'weight', 'stone', 'kt', 'image'];

    public function category() {
    	return $this->belongsTo(Category::class,'category_id');
	}
}
