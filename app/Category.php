<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $primaryKey = 'category_id';

    protected $fillable = ['name', 'image'];

    public function Product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
