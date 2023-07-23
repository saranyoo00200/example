<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tb_product';

    protected $guarded = ['id'];

    protected  function  serializeDate(DateTimeInterface  $date)
    {
        return  $date->format('Y-m-d H:i:s');
    }
}