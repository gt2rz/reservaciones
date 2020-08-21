<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = ['restaurants_id', 'reservation_date', 'reservation_time', 'amount_of_people'];
    protected $hidden = ['created_at', 'update_at', 'deleted_at'];

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }
}
