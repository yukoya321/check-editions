<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class History extends Model
{
    protected $fillable = ['word'];
    
    public function user(){
        return $this->belongsToMany('App\User');
    }
}
