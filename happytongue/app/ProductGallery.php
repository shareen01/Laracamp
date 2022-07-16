<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;

class ProductGallery extends Model
{
    protected $fillable = ['photos','products_id'];
        
    protected $hidden = [];
    public function product()
    {
        return $this->belongsTo(Product::class,'products_id','id');
    }
}