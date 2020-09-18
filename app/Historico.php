<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    protected $table = 'historico';
    protected $fillable = ['id_cliente','id_medidor','fecha'];

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }
    public function medidores()
    {
        return $this->hasMany('App\Medidor');
    }
}
