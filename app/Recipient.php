<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Recipient extends Model
{
    protected $table = 'recipient';

    use SoftDeletes;
}
