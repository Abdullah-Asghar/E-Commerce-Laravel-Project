<?php

namespace App;
use App\ProductsAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function attributes(){
    	return $this->hasMany('App\ProductsAttribute','product_id');
    }

    // public function categories(){
    //     return $this->hasMany(Category::class, 'parent_id');
    // }
}
