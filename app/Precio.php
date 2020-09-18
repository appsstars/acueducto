<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = 'precio';
    protected $fillable = ['cargo_fijo','subsidio','precio_metro','estado'];
}
