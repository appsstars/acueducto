<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoCredito extends Model
{
    protected $table = 'pago_credito';
    protected $fillable = ['id_credito','valor_abono','fecha_abono'];

    public function creditos()
    {
        return $this->hasMany('App\Credito');
    }
    
}
