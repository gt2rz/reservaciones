<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'address', 'city', 'photo_url'];
    protected $hidden = ['created_at', 'update_at', 'deleted_at'];
    
    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }
}
