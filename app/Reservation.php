<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = ['restaurant_id', 'reservation_date', 'table_name','amount_of_people'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

}
