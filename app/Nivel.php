<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'nivel';
    protected $fillable = ['tipo','porcentaje'];


    public function clientes()
    {
        return $this->belongsTo('App\Cliente');
    }
}
