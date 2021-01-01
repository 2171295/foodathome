<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'phone',
        'nif',
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User', 'id');
    }
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }
}
