<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $fillable = ["photos", "products_id"];

    protected $hidden = [];

    public function product()
    {
        return $this->belongsTo(Product::class, "products_id", "id");
        // jika ingin hapus tapi ingin tetap bisa mengambil data maka ditambahin aja ->withTrashed() sebelum ;
    }
}
