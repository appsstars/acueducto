<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'factura';
    protected $fillable = ['lectura','periodo','fecha_factura','fecha_pago','ano','estado','lectura_final','valor','consumo','total_pagar','updated_at'];

    public function facturaciones()
    {
        return $this->belongsTo('App\Facturacion');
    }
}
