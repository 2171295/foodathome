<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'notes', 'customer_id','total_price',
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product','order_items')
            ->using('App\Models\OrderItems')
            ->withPivot([
                'quantity',
                'sub_total_price'
            ]);
    }

    public function cooker(){
        return $this->belongsTo('App\Models\User','prepared_by');
    }

    public function deliveryman(){
        return $this->belongsTo('App\Models\User','delivered_by');
    }
    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

    public function getCustomer(){
        return Customer::findOrFail($this->customer_id);
    }

}
