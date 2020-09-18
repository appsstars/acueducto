<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;

class AppController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('auth.login');
    }

    public function update(){
    	$sql = Factura::all();

    	foreach ($sql as $key => $value) {
    		$id = $value->id;
    		$updated_at = $value->updated_at;

    		if($value->estado==2){
    			$fecha_pago = substr($updated_at, 0,10);
	    		$factura = Factura::find($id);
	    		$factura->fecha_pago = $fecha_pago;
	    		$factura->updated_at = $updated_at;
	    		$factura->save();
    		}
    		
    		
    	}
    }



}
