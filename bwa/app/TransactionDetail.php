<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TransactionDetail extends Model
{
    use HasApiTokens, HasFactory,  HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'transactions_id',
       'products_id',
       'price',
       'shipping_status',
       'resi',
       'code',
       'address_one',
       'address_two',
       'provinces_id',
       'regencies_id',
       'zip_code',
       'country',
       'phone_number'

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public function product(){
        return $this->hasOne(Product::class,'id','products_id');
    }
    public function transaction(){
        return $this->hasOne(Transaction::class,'id','transactions_id');
    }
}
