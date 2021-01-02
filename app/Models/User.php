<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'photo_url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customers()
    {
        return $this->hasOne('App\Models\Customer', 'id');
    }

    public function cooker_orders(){
        return $this->$this->hasMany('prepared_by');
    }

    public function deliveryman_orders(){
        return $this->$this->hasMany('delivered_by');
    }

    public function typeToString(){
        switch ($this->type) {
            case 'EC':
                return 'Cooker';
            case 'EM':
                return 'Manager';
            case 'ED':
                return 'Deliveryman';
        }
    }

    public function blockedToStr(){
        switch ($this->blocked) {
            case 1:
                return 'Yes';
            case 0:
                return 'No';
        }
    }
}
