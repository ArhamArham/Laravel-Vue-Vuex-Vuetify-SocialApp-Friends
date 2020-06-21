<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Profile;
class Post extends Model
{
    //
    protected $guarded=[];

    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }
    public function comments()
    {
    	return $this->hasMany('App\Comment')->orderBy('created_at', 'desc');
    }
    public function follow()
    {
        return $this->hasMany('App\Follow');
    }
    public function replies()
    {
        return $this->belongsTo('App\Reply');
    }
}
