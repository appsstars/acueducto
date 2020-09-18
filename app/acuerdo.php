<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class acuerdo extends Model
{
    protected $table = 'acuerdo';
    protected $fillable = ['valor','cuotas','fecha'];

    public function facturaciones()
    {
        return $this->belongsTo('App\Facturacion');
    }
}
