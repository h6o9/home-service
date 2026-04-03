<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $guarded = [];
    
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function photos()
    {
        return $this->hasMany(ShopPhoto::class);
    }

    public function jobs()
    {
        return $this->hasMany(StaffJob::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(ShopPhoto::class)->where('is_primary', true);
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'electrician' => 'Electrician',
            'wifi_controller' => 'WiFi Installer',
            'solar' => 'Solar',
            'plumber' => 'Plumber',
        ];
        
        return $labels[$this->category] ?? ucfirst($this->category);
    }
}
