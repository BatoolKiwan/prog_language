<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'name', 'regular_price','category_id','quentity','price','image','information_comm','expirat_date'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

 /*    public function getRouteKeyName()
    {
        return 'slug';

    } */
    public function getImageAttribute($value)
    {
       if (filter_var($value, FILTER_VALIDATE_URL)) {
           return $value;
       }
       return asset("storage/{$value}");
    }
}
