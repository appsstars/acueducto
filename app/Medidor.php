<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medidor extends Model
{
    protected $table = 'medidor';
    protected $fillable = ['id','marca','serial','lectura_inicial','estado'];


    public function puntos()
    {
        return $this->belongsTo('App\PuntoAgua');
    }
    public function facturaciones()
    {
        return $this->belongsTo('App\Facturacion');
    }
    public function historicos()
    {
        return $this->belongsTo('App\Historico');
    }


}
