<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    protected $fillable = ['user_id', 'status'];

    public function user() {
    	return $this->belongsTo(User::class,'user_id');
	}
}
