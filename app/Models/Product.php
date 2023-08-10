<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category_type() {
        return $this->belongsTo(CategoryType::class);
    }

    public function category_gender() {
        return $this->belongsTo(CategoryGender::class);
    }

    public function category_jewel() {
        return $this->belongsTo(CategoryJewel::class);
    }

    public function category_metal() {
        return $this->belongsTo(CategoryMetal::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

//    public function shops() {
//        return $this->hasManyThrough(Shop::class, ProductShop::class);
//    }

    public function product_shops() {
        return $this->hasMany(ProductShop::class);
    }

    public function carts() {
        return $this->hasMany(Cart::class);
    }
}
