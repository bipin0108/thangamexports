<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $primaryKey = 'sub_category_id';

    protected $fillable = ['category_id', 'name', 'image'];

    public function category() {
    	return $this->belongsTo(Category::class,'category_id');
	}
}
