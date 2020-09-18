<?php

namespace App\Http\Controllers;

use App\Cliente;
use DB;
use App\Credito;
use Illuminate\Http\Request;
use App\Http\Requests\CreditoRequest; 
use App\PuntoAgua;
use PhpParser\Node\Expr\New_;
use Session;
use App\Facturacion;

class CreditoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd('aca estpoy');
       
      //dd($creditos);
 
      if(!empty($_REQUEST['buscar'])){
          $creditos = DB::table('medidor as m')
          ->join('punto_agua as pt','pt.id_medidor','=','m.id')
          ->join('cliente as c','c.id','pt.id_cliente')
          ->where('m.id','LIKE','%'.$_REQUEST['buscar'].'%')
          ->select('m.id as id_medidor','c.nombre','c.primer_apellido','c.segundo_apellido','c.documento','pt.zona','c.telefono','pt.id as id_punto')->get();
          //dd($creditos);

          }else{
             $creditos = DB::table('medidor as m')
               ->join('punto_agua as pt','pt.id_medidor','m.id')  ///
              ->join('cliente as c','c.id','pt.id_cliente')
              ->select('m.id as id_medidor','c.nombre','c.primer_apellido','c.segundo_apellido','c.documento','pt.zona','c.telefono','pt.id as id_punto')->get();
          }


        return view('credito.index',compact("creditos"));
    }

    public function lista($id)
    {
        $punto = PuntoAgua::find($id);
        $credito = DB::table('credito as cr')
        ->join('cliente as cl','cl.id','=',$punto->id_cliente)
        ->join('nivel as n','n.id','=','cl.id_nivel')
        ->where('cr.id_punto_agua','=',$punto->id)
          ->select('cr.*','cr.id as id_credito','n.tipo','cl.nombre','cl.primer_apellido','cl.segundo_apellido','cl.documento','cl.telefono')->get();
                  // dd($credito);

         return view('credito.detalles',compact('credito'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function actualizar($id, $valor){
        $credito = Credito::find($id);
        $credito->valor_cuota = $valor;
        $credito->save();
        return redirect()->back();
    }


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
    public function store(CreditoRequest $request)
    {
       //dd($request->all());


        //verificar si tiene creditos

        $creditos = DB::table('credito')
        ->where('id_punto_agua','=',$request->id_punto)
        ->where('estado','=','1')
        ->select('*')->get();
        
        if(count($creditos)>0){
            $valor_anterior = $creditos[0]->valor_cuota;
            $nuevo_valor = $valor_anterior + $request->valor;
            //
            $credito = Credito::find($creditos[0]->id);
            $credito->valor_cuota = $nuevo_valor;
            $credito->valor = $nuevo_valor;
            $credito->save();
        }else{
               $valor_cuota = ($request->valor / $request->cuotas);
               $credito = New Credito();
               $credito->id_punto_agua = $request->id_punto;
               $credito->valor = $request->valor;
               $credito->cuota = $request->cuotas;
               $credito->estado = '1';
               $credito->descripcion = $request->descripcion;
               $credito->fecha_pago = $request->fecha;
               $credito->valor_cuota = $valor_cuota;
               $credito->save();
               //sacar id_credito
               $punto_agua = DB::table('punto_agua')
               ->where('id','=',$request->id_punto)
               ->select('*')->get();

               //verificar facturacion
               $id_medidor = $punto_agua[0]->id_medidor;


               $facturacion = DB::table('facturacion')
               ->where('id_medidor','=',$id_medidor)
               ->select('*')->get();

               $fac = Facturacion::find($facturacion[0]->id);
               $fac->id_credito = $credito->id;
               $fac->save();
        }
       
       // dd($valor_cuota);

          
           Session::flash('credito_crear','Credito creado con exito.');
           return redirect()->back();




    //     $periodo = substr($request->fecha,5,-3);
    //     $anno = substr($request->fecha,0,-6);

    //     $fecha = $anno.'-'.$periodo.'-27';
    //     $request['fecha'] = $fecha;
    //     if($periodo==12){
    //         $periodo = '01';
    //         $anno = $anno + 1;
    //     }elseif($periodo<9){
    //         $periodo = $periodo + 01; $periodo = '0'.$periodo;
    //     }else{
    //         $periodo = $periodo + 1;$periodo = ''.$periodo;
    //     }

    //     $fecha_pago = $anno.'-'.$periodo.'-25';
    //     $valor_pagar = $request->valor/$request->cuotas;

    //     $clientes = DB::table('credito as cr')
    //     ->where('cr.id_cliente','=',$request->id_cliente)
    //     ->select('*')->get();
    //    //dd($request->id_cliente);
    //     if(count($clientes)==0){
    //        // dd('te creare');
    //     }elseif($clientes[0]->estado==1){
    //       // dd($clientes[0]->estado);
    //        $estado=$clientes[0]->estado;
    //        //dd($estado);
    //        if ($estado==1) {
    //            //dd('session');
    //           Session::flash('rechazado','Ya tiene un Credito');
    //           return redirect()->back();
    //        }

    //     }

    //     $credito = New Credito();
    //         $credito->id_cliente = $request->id_cliente;
    //         $credito->valor = $request->valor;
    //         $credito->cuotas = $request->cuotas;
    //         $credito->fecha = $request->fecha;
    //         $credito->estado = '1';
    //         $credito->fecha_pago = $fecha_pago;
    //         $credito->valor_cuota = $valor_pagar;
    //         $credito->save();
    //         Session::flash('credito_crear','Credito creado con exito.');
    //         return redirect()->back();

    //     //dd($clientes);

    //     // dd($estado);
    //     //dd($request->all());


    //   if (!$clientes) {
    //       dd('hola');
    //   }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function show($id_punto)
    {
       $table = DB::table('credito')
       ->where('id_punto_agua','=',$id_punto)
       ->select('*')->get();
       dd();

       $id_credito = $table[0]->id;

       $credito = Credito::find($id_credito);
       $credito->estado = 2;
       $credito->save();
       
       return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function edit(Credito $credito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $credito)
    {
        $credito = Credito::find($credito);
        $valor_actual = $credito->valor;
        $credito->valor = $valor_actual - $request->valor_abono;
        $credito->valor_cuota = $valor_actual - $request->valor_abono;


        if($valor_actual >= $request->valor_abono){
          Session::flash('mensaje','Abono realizado con exito.');
  
        }else{
          Session::flash('error','EL valor de la cuota no puede superar al valor de la deuda total.');
          return redirect()->back();
        }

        if($credito->valor==0){
            $credito->estado = 2;
        }

        $credito->save();
        return redirect()->back();  // no analizamos bien el algoritmo paso jajja POR QUE   el valor del formulario no puede superarra al credito jajajjaj y si paso
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Credito  $credito
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          $facturacion = DB::table('facturacion')->where('id_credito','=',$id)->select('*')->get();

          
          foreach ($facturacion as $key => $value) {
            $id_f = $value->id;
            $fac = Facturacion::find($id_f);
            $fac->id_credito = null;
            $fac->save();
          }

          $credito = Credito::find($id);
          $credito->delete();
         return redirect()->back();
    }
}
