<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;
use App\Facturacion;
use DB;

class UpdateController extends Controller
{
    public function add_punto_agua_facturacion(){
    	//medidores
    	$puntos = DB::table('punto_agua as pt')
		->join('medidor as m','m.id','=','pt.id_medidor')
		->select('*','pt.id as id_punto','pt.estado as estado_punto')->get();
		foreach ($puntos as $key => $punto) {
			$id_punto = $punto->id_punto;
			$id_medidor = $punto->id_medidor;
			$id_cliente = $punto->id_cliente;

			$fecha_creacion_punto = $punto->created_at;

			$facturacion = DB::table('facturacion as fn')
			->join('factura as f','f.id','=','fn.id_factura')
			->where('fn.id_cliente','=',$id_cliente)
			->select('fn.id as id_facturacion')->get();

			foreach ($facturacion as $key => $facturacion_list) {
				$id_facturacion = $facturacion_list->id_facturacion;

				$facturacion_cliente = Facturacion::find($id_facturacion);
				$facturacion_cliente->id_punto_agua = $id_punto;
				$facturacion_cliente->save();
			}

				
			//verificar puntos venta
		}

		echo 'success';
    }
}
