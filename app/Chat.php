<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';

    public function setting(){
        return $this->belongsTo('App\Setting');
    }
}
