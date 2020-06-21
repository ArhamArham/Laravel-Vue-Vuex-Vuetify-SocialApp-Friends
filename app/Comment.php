<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $guarded=[];

	public function user()
    {
    	return $this->hasOne('App\user','id','user_id');
    }
    public function replies()
    {
    	return $this->hasMany('App\Reply');
    }
}
