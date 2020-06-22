<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Profile extends Model
{

    //
    protected $guarded=[];
    protected $primaryKey = 'user_id';
    public function user()
    {
        return $this->belongsTo('App\Profile');
    }
    public function follows()
    {
        return $this->belongsToMany(User::class,'follows','user_id','follower_id')->withTimestamps();
    }
    
     
}
