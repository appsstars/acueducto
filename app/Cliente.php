<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $fillable = ['id_nivel','nombre','primer_apellido','segundo_apellido','documento','direccion','telefono','estado'];

    public function facturaciones()
    {
        return $this->belongsTo('App\Facturacion');
    }
    public function historicos()
    {
        return $this->belongsTo('App\Historico');
    }

    public function puntos()
    {
        return $this->belongsTo('App\PuntoAgua');
    }

    public function niveles()
    {
        return $this->hasMany('App\Nivel');
    }
}
