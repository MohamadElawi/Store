<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    protected  $table='comments';

    protected $fillable=['body','product_id','user_id'];

    protected $hidden=['created_at','updated_at','product_id'];


    public function product(){
        return $this->belongsto('App\Models\product','product_id');
    }

    public function user(){
        return $this->belongsto('App\User','user_id');
    }
}
