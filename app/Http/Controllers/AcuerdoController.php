<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;
use App\Http\Requests\FacturaRequest;
use Session;
use DB;
use App\Cliente;
use App\Precio;
use PDF;
use App\Nivel;
use App\Medidor;
use App\Facturacion;

class AcuerdoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //periodo 03
    {
        //$factura = new Factura();
       // $periodo = substr($request->fecha_factura,5,-3);
        //$anno = substr($request->fecha_factura,0,-6);
        //$dia = substr($request->fecha_factura.' ', -3,-1);

        //facturacion
        set_time_limit(0);
        for ($i = 3; $i<=4;$i++) { 
             $facturacion = DB::table('facturacion as fn')
              ->join('factura as f','f.id','=','fn.id_factura')
              ->join('medidor as m','m.id','=','fn.id_medidor')
              ->where('f.periodo','=','0'.$i)
              ->select('f.id','fn.id_cliente','fn.id_medidor','f.total_pagar','f.consumo')->get();

              //
              $precio = Precio::where('estado','2')->select('*')->get();
              $precio_metro = $precio[0]['precio_metro'];
              $cargo_fijo =  $precio[0]['cargo_fijo'];

              //leer array()
              foreach ($facturacion as $key => $factura) {
                $consumo = $factura->consumo;
                $id_cliente = $factura->id_cliente;


                  $nivel = DB::table('cliente as c')
                  ->join('nivel as n','n.id','=','c.id_nivel')
                  ->where('c.id','=',$id_cliente)
                  ->select('*')->get();


                  //
                   if($consumo>11){
                      // dd($dato);
                       $metros_adicionales = $consumo - 11;
                       $precio_normal = $metros_adicionales * $precio_metro;
                       $valor_basico = 11 * $precio_metro;
                       $valor_consumo = $valor_basico + $precio_normal;
                   }else{
                        $valor_basico = $consumo * $precio_metro;
                        $valor_consumo = $valor_basico;
                        $precio_normal = 0;
                   }
                  $subsidio_consumo = ($valor_basico * $nivel[0]->porcentaje) / 100;
                  $valor_pagar_sub = $valor_basico - $subsidio_consumo;

                  //cargos fijos
                  $subsidio_cargo = ($cargo_fijo * $nivel[0]->porcentaje) / 100;//30/110/0
                  $subsidio_pagar_cargo = $cargo_fijo - $subsidio_cargo;///pagar   $/////00 7.800

                $total_temporal = $valor_pagar_sub + $precio_normal + $subsidio_pagar_cargo; 

                $factura_usuario = Factura::find($factura->id);
                $factura_usuario->total_pagar = $total_temporal;
                $factura_usuario->save();

              }
        }
            
          echo 'path final - Base de datos actualizada.';

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\acuerdo  $acuerdo
     * @return \Illuminate\Http\Response
     */
    public function show(acuerdo $acuerdo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\acuerdo  $acuerdo
     * @return \Illuminate\Http\Response
     */
    public function edit(acuerdo $acuerdo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\acuerdo  $acuerdo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, acuerdo $acuerdo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\acuerdo  $acuerdo
     * @return \Illuminate\Http\Response
     */
    public function destroy(acuerdo $acuerdo)
    {
        //
    }
}
