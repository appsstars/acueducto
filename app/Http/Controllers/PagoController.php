<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Cliente;
use App\Credito;
use App\Precio;
use App\Nivel;
use Illuminate\Http\Request;
use DB;
use Session;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //clientes
        $clientes = DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->join('cliente as c','c.id','=','fn.id_cliente')
        ->where('f.estado','=','1')
        ->select('c.id','c.nombre','c.nombre','c.primer_apellido','c.segundo_apellido','c.documento')->groupBy('c.id')->get();


        $facturacion_total = DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->join('cliente as c','c.id','=','fn.id_cliente')
        ->select('fn.id_cliente','fn.otros','fn.id_medidor','f.id as id_factura','c.id','c.nombre','c.nombre','c.primer_apellido','c.segundo_apellido','c.documento','f.periodo','f.ano')->orderBy('fn.id','ASC')->get();

    // dd($facturacion_total);

        $clientes_f = Cliente::all();
        $consumo = array();
        $dato = array();

        $dato['otros_cobros'] = 0;


        foreach ($facturacion_total as $c) {
           $id_cliente = $c->id_cliente;
            $id_factura = $c->id_factura;

            $dato['otros_cobros'] = $c->otros;


           $facturacion = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_factura','=',$id_factura)
            ->select('*','f.id as id_factura','m.id as codigo_medidor','m.id')->get();

            $nivel = DB::table('cliente as c')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->where('c.id','=',$id_cliente)
            ->select('*')->get();
            //
            $medidor =  DB::table('punto_agua as pt')
                ->join('cliente as cl','cl.id','=','pt.id_cliente')
                ->join('medidor as md','md.id','=','pt.id_medidor')
                ->join('facturacion as fn','fn.id_medidor','=','md.id')
                ->join('nivel as n','n.id','=','cl.id_nivel')
                ->where('fn.id_factura','=',$facturacion[0]->id_factura)
              //  ->where('cl.id_nivel','=',3)
                ->select('md.lectura_inicial as li','cl.id_nivel','md.id')->get();

              //  dump($medidor);





            //
            $precio = Precio::all()->toArray();
            $dato['precio_metro'] = $precio[0]['precio_metro'];
            $dato['cargo_fijo'] = $precio[0]['cargo_fijo'];

            $mts = '11';



            $dato['subsidio'] = $precio[0]['subsidio'];
            $dato['id_cliente'] = $id_cliente;
            $dato['descuento_nivel'] = $nivel[0]->porcentaje;



            $ultima_medicion = 0;


            if(count($facturacion)==0){
                $lectura =  $medidor[0]->li;
                $dato['lectura_anterior'] = '0';
                $dato['lectura_actual'] = $lectura;
                $dato['consumo'] = '0';
                $dato['fecha_lectura'] = '0000-00-00';
                $dato['fecha_limite'] = '0000-00-00';
                $dato['numero_lectura'] = '####';


            }elseif(count($facturacion)==1){
                $dato['lectura_anterior'] = $medidor[0]->li;
                $dato['lectura_actual'] = $facturacion[0]->lectura;
                $dato['fecha_lectura'] = $facturacion[0]->fecha_factura;
                $dato['consumo'] = $dato['lectura_actual'] - $dato['lectura_anterior'];
                $dato['fecha_limite'] = $facturacion[0]->fecha_limite;
                $dato['numero_lectura'] = $id_factura;


            }else if(count($facturacion)>=2){
                $facturacion_ = DB::table('facturacion as fn')//lectura actual
                ->join('factura as f','f.id','=','fn.id_factura')
                ->where('fn.id_cliente','=',$id_cliente)
                ->select('*','f.id as id_factura')->get()->last();
                //
               $cant = count($facturacion) - 2;
               $dato['lectura_anterior'] = $facturacion[$cant]->lectura;
               $dato['lectura_actual'] = $facturacion_->lectura;
               $dato['fecha_lectura'] = $facturacion_->fecha_factura;
               $dato['numero_lectura'] = $facturacion_->id_factura;
               $dato['consumo'] = $dato['lectura_actual'] - $dato['lectura_anterior'];
                $dato['fecha_limite'] = $facturacion[0]->fecha_limite;

            }

            //
            $dato['codigo_medidor'] =  $medidor[0]->id;
            $dato['id_nivel'] =   $nivel[0]->tipo;

            $dato['metros_adicionales'] = 0;
            $dato['precio_normal'] = 0;
            $dato['valor_basico'] = 0;
            $dato['valor_consumo'] = 0;
            $dato['valor_'] = 0;
            $dato['subsidio_cargo_fijo'] = 0;
            $cargo_fijo = '7465';
            //$descuento_fijo = '';

            //subsidio consumo
            if($dato['consumo']>11){
                $dato['metros_adicionales'] = $dato['consumo'] - 11;
                $dato['precio_normal'] = $dato['metros_adicionales'] * $dato['precio_metro'];
                $dato['valor_basico'] = 11 * $dato['precio_metro'];
                $dato['valor_consumo'] = $dato['valor_basico'] + $dato['precio_normal'];
                //
                $dato['subsidio_consumo'] =  ($dato['valor_basico'] * $nivel[0]->porcentaje) / 100;
            }else{
                 $dato['valor_basico'] = $dato['consumo'] * $dato['precio_metro'];
                 $dato['valor_consumo'] = $dato['valor_basico'];
                 $dato['subsidio_consumo'] =  ($dato['valor_basico'] * $nivel[0]->porcentaje) / 100;
            }


            //subsidio cargos fijo

                    $id_nivel_tipo = $nivel[0]->tipo;

        if($id_nivel_tipo == '11' || $id_nivel_tipo == '12'){
            $dato['subsidio_cargo_fijo'] = 0;
        }else{
            $dato['subsidio_cargo_fijo_temp'] = ($precio[0]['cargo_fijo'] * $nivel[0]->porcentaje) / 100;
            $dato['subsidio_cargo_fijo'] = $precio[0]['cargo_fijo'] - $dato['subsidio_cargo_fijo_temp'];
        }






            array_push($consumo, $dato);

        }

        return view('pago.index',compact('consumo','facturacion_total'));


        //descuentos
        //dd($nivel);





//dd($consumo);

       // dd($clie);

         // dd($clientes);


        //PDF::setOptions(['dpi' => '1', 'defaultFont' => 'sans-serif']);
        //$pdf = PDF::loadView('pdf.index', compact('consumo','facturacion_total'));
        //$pdf->setPaper('A4', 'landscape');
       // return $pdf->download('demo.pdf');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!empty($_REQUEST['buscar']) ){
             $clientes_f  =  DB::table('facturacion as fn')
            ->join('factura as f','f.id','fn.id_factura')
            ->join('cliente as c','c.id','=','fn.id_cliente')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->join('punto_agua as pt','pt.id_medidor','=','fn.id_medidor')
            //->Orwhere('f.estado','LIKE','%'.'1'.'%')
            ->Orwhere('c.documento','LIKE','%'.$_REQUEST['buscar'].'%')
            ->Orwhere('fn.id_medidor','LIKE','%'.$_REQUEST['buscar'].'%')
            ->select('*','f.estado as estado_factura')->get();
            //dd($clientes_f);
        }else{
         $anno = date('yy');
         $mes = date('m') - 1;
        if($mes==0){
            $anno = $anno - 1;
            $mes = 12;
        }
        if($mes>=1 && $mes<=9){
            $mes = '0'.$mes;
        }

          
                        
             $clientes_f  =  DB::table('facturacion as fn')
            ->join('factura as f','f.id','fn.id_factura')
            ->join('cliente as c','c.id','=','fn.id_cliente')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->join('punto_agua as pt','pt.id_medidor','=','fn.id_medidor')
            ->where('f.estado','=','1')
            ->where('f.periodo','=',$mes)
            ->where('f.ano','=',$anno)
            ->select('*','f.estado as estado_factura')->get();
            //dd($clientes_f);
        }
        return view('pago.create',compact('clientes_f'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        date_default_timezone_set('America/Bogota');
        if(!$request->id_factura){
            return redirect()->back();
        }
        $id_medidor = $request->id_medidor;
        $ultima_facturacion =  DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->join('precio as p','p.id','=','fn.id_precio')
        ->join('cliente as c','c.id','=','fn.id_cliente')
        ->join('nivel as n','n.id','=','c.id_nivel')
        ->where('fn.id_medidor','=',$id_medidor)
        ->where('f.estado','=','1 ')
        ->select('*','fn.id as id_facturacion','f.id as id_factura')->get()->last();

        $id_ultima_facturacion = $ultima_facturacion->id_factura;
        $id_credito = '';
        
        

        $id_credito = '';

         $credito = DB::table('credito as cr')
        ->join('punto_agua as pa','pa.id','cr.id_punto_agua')
        ->join('medidor as m','m.id','pa.id_medidor')
        ->where('cr.estado','=','1')
        ->where('m.id','=',$ultima_facturacion->id_medidor)
        ->select('*','m.id as id_medidor','cr.valor','cr.id as id_credito')->get();




        foreach ($credito as $key => $value) {
            if($value->id_credito==''){}else{
               $id_credito = $value->id_credito; 
            }
        }





        

        $facturas = $request->id_factura;
        $valor_total = 0;
        foreach ($facturas as $key => $value) {
            $info_facturas = explode('-', $value);
            $id_factura = $info_facturas[1];
            $valor = $info_facturas[0];
            //
            //dd($value);
            $valor_total = $valor_total + $valor;
            $factura = Factura::find($id_factura);
            $factura->fecha_pago = date('yy-m-d');
            if($id_factura==$id_ultima_facturacion){
                $valor_total = $valor_total + $request->otros_cobros;
                 $factura->valor = $valor_total;
                 $factura->estado = '2';          
                $factura->save();
            }else{
                 $factura->estado = '2';            
                 $factura->save();
            }
           
        }

        if($id_credito!=''){
            $credito = Credito::find($id_credito);
            $valor_restante = 0;
            if($credito->valor_cuota > $request->otros_cobros){
                $valor_restante =  $credito->valor_cuota - $request->otros_cobros;
               $credito->valor_cuota =  $valor_restante;
               $credito->save();
            }elseif($credito->valor_cuota == $request->otros_cobros){
                $credito = Credito::find($id_credito);
                $credito->valor_cuota = 0;
                $credito->estado = '2';
                $credito->save();

               // dd($credito);
            }else{
                Session::flash('error','¡!EL valor del credito supera al pendiente...!');
            }
        }
            

        Session::flash('success','¡!Pago realizado con exito!');
        return redirect()->back();

        //servicios
        //objetivo sitio web
        //informatvo 
        //landing pages


    }

    /**
     * Display the specified resource. 
     * @param  \App\Nivel  $nivel
     * @return \Illuminate\Http\Response
     */
    public function show($id_cliente)
    {
        $id_medidor = $id_cliente;
        //v1 = metros consumo
        $data_g = array();
        $facturacion = DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->join('precio as p','p.id','=','fn.id_precio')
        ->join('cliente as c','c.id','=','fn.id_cliente')
        ->join('nivel as n','n.id','=','c.id_nivel')
        ->where('fn.id_medidor','=',$id_medidor)
        ->where('f.estado','=','1 ')
        ->select('*','fn.id as id_facturacion','f.id as id_factura')->get();
        //salto de la lectura inicial contador y tiene mas facturas asociadoas 2 o mas

      /*  $creditos = DB::table('credito as c')
        ->join('facturacion as fn','fn.id_credito','=','c.id')
        ->join('factura as f','f.id','=','fn.id_factura')
       // ->where('pt.id_medidor','=',$c->id_medidor)
        ->where('c.estado','=','1')
        ->select('fn.id as id_facturacion','c.id as id_credito','c.*','fn.*')->get();



        */

        //dd($facturacion);

        $creditos = DB::table('credito as cr')
        ->join('punto_agua as pa','pa.id','cr.id_punto_agua')
        ->join('medidor as m','m.id','pa.id_medidor')
        ->where('cr.estado','=','1')
        ->select('*','m.id as id_medidor','cr.valor')->get();

        //dd($creditos);
        
        

        
        return view('pago.list_pagos',compact('facturacion','creditos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Nivel  $nivel
     * @return \Illuminate\Http\Response
     */
    public function edit(Nivel $nivel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nivel  $nivel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nivel $nivel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Nivel  $nivel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nivel $nivel)
    {
        //
    }
}
