<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuntoAgua extends Model
{
    protected $table = 'punto_agua';
    protected $fillable = ['id_cliente','id_medidor','zona'];

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }

    public function medidores()
    {
        return $this->hasMany('App\Medidor');
    }

}
