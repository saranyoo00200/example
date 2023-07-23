<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'tb_shop';

    protected $guarded = ['id'];

    protected  function  serializeDate(DateTimeInterface  $date)
    {
        return  $date->format('Y-m-d H:i:s');
    }
}
