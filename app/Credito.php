<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table = 'credito';
    protected $fillable = ['id_punto_agua','valor','cuota','estado','descripcion','fecha_pago','valor_cuota'];
    protected $dates = ['fecha_pago'];

    public function facturaciones()
    {
        return $this->belongsTo('App\Facturacion');
    }
    public function puntos()
    {
        return $this->hasMany('App\PuntoAgua');
    }
}
