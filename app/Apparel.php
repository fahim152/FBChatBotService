<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apparel extends Model
{
    protected $table = 'apparel';

    use SoftDeletes;
}
