<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItems extends Pivot
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'product_id', 'quantity','unit_price','sub_total_price'
    ];

    protected $table = 'order_items';

    public $incrementing = true;
    public $timestamps = false;

    public function getProduct(){
        return Product::findOrFail($this->product_id);
    }

    public function getOrder(){
        return Order::findOrFail($this->order_id);
    }

}
