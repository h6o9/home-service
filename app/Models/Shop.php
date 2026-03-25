<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //

	protected $guarded = [];
	
	public function staff()
	{
		return $this->belongsTo(Staff::class, 'staff_id');
	}

	public function photos()
	{
		return $this->hasMany(ShopPhoto::class);
	}
}
