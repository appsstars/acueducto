<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    protected $table = 'facturacion';
    protected $fillable = ['id_cliente','id_factura','id_medidor','id_credito','id_acuerdo','id_precio','fecha_facturacion','fecha_limite'];//metro adicional

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }
    public function medidores()
    {
        return $this->hasMany('App\Medidor');
    }

    public function facturas()
    {
        return $this->hasMany('App\Factura');
    }

    public function creditos()
    {
        return $this->hasMany('App\Credito');
    }

    public function acuerdos()
    {
        return $this->hasMany('App\Acuerdo');
    }
}
