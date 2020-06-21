<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    //
    protected $guarded=[];
    public function user()
    {
    	return $this->hasOne('App\user','id','user_id');
    }
}
