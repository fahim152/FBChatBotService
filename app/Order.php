<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $table = 'order';

    use SoftDeletes;

    public function apparel(){
        return $this->belongsTo('App\Apparel');
    }
}
