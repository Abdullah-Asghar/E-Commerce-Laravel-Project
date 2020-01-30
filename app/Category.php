<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;
use App\Product;

class Category extends Model
{
    protected $table = 'category';

    public function categories(){
    	return $this->hasMany('App\Category','parent_id');
    }
}
