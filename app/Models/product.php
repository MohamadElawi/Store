<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table ='products';

    protected $fillable =['name','address','category','count','price','newPrice','photo','viewers','user_id','expiration'];

    protected $hidden =['created_at','updated_at'];

    public $withCount = ['comments', 'likes'];

    public function user(){
        return $this->beLongsTo('App\User','user_id','id');
    }
    public function comments(){
        return $this->hasMany('App\Models\comment','product_id','id');
    }
    public function likes(){
        return $this->hasMany('App\Models\like','product_id','id');
    }

    

}
