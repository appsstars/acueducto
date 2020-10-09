<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Cliente;
use App\Precio;
use Illuminate\Http\Request;
use DB;
use PhpParser\Node\Stmt\Else_;
use Session;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        //
        $anno = '';
        $mes = '';
        //
        if(!empty($_REQUEST['reporte'])){
            $reporte = $_REQUEST['reporte'];

             if(!empty($_REQUEST['mes']) && !empty($_REQUEST['anno'])  ){
                if($mes>=1 || $mes<=9){
                    $mes = '0'.$_REQUEST['mes'];
                    $anno = $_REQUEST['anno'];
                }
             }

            if($reporte==1 || $reporte ==2){
                if(!empty($_REQUEST['mes'])){
                    if($reporte==1 && $_REQUEST['tipo']!=''){
                        if($_REQUEST['tipo']==1){
                             $facturacion_total = DB::table('facturacion as fn')
                            ->join('factura as f','f.id','=','fn.id_factura')
                            ->join('cliente as c','c.id','=','fn.id_cliente')
                            ->join('precio as p','p.id','=','fn.id_precio')
                            ->join('nivel as n','n.id','=','c.id_nivel')
                            // ->where('f.estado','=','1')
                            ->where('f.periodo','=',$mes)
                             ->where('f.ano','=',$anno)
                            ->select(
                                'fn.id as id_facturacion',
                                'fn.*',
                                'f.*',
                                'c.*',
                                'p.*',
                                'n.*'
                            )->get();
                        }else if($_REQUEST['tipo']==2){
                             $facturacion_total = DB::table('facturacion as fn')
                            ->join('factura as f','f.id','=','fn.id_factura')
                            ->join('cliente as c','c.id','=','fn.id_cliente')
                            ->join('precio as p','p.id','=','fn.id_precio')
                            ->join('nivel as n','n.id','=','c.id_nivel')
                            ->where('f.estado','=','1')
                            ->where('f.periodo','=',$mes)
                             ->where('f.ano','=',$anno)
                            ->select(
                                'fn.id as id_facturacion',
                                'fn.*',
                                'f.*',
                                'c.*',
                                'p.*',
                                'n.*'
                            )->get();
                        }else if($_REQUEST['tipo']==3){
                            $facturacion_total = DB::table('facturacion as fn')
                            ->join('factura as f','f.id','=','fn.id_factura')
                            ->join('cliente as c','c.id','=','fn.id_cliente')
                            ->join('precio as p','p.id','=','fn.id_precio')
                            ->join('nivel as n','n.id','=','c.id_nivel')
                            ->where('f.estado','=','2')
                            ->where('f.periodo','=',$mes)
                             ->where('f.ano','=',$anno)
                            ->select(
                                'fn.id as id_facturacion',
                                'fn.*',
                                'f.*',
                                'c.*',
                                'p.*',
                                'n.*'
                            )->get();

                           // dd($facturacion_total);
                        }

                    }else{

                      $facturacion_total = DB::table('facturacion as fn')
                      ->join('factura as f','f.id','=','fn.id_factura')
                        ->join('cliente as c','c.id','=','fn.id_cliente')
                        ->join('precio as p','p.id','=','fn.id_precio')
                        ->join('nivel as n','n.id','=','c.id_nivel')
                        // ->where('f.estado','=','1')
                        ->where('f.periodo','=',$mes)
                         ->where('f.ano','=',$anno)
                        ->select(
                            'fn.id as id_facturacion',
                            'fn.*',
                            'f.*',
                            'c.*',
                            'p.*',
                            'n.*'
                        )->get();
                    }

                   $creditos = DB::table('credito as c')
                    ->join('facturacion as fn','fn.id_credito','=','c.id')
                    ->join('factura as f','f.id','=','fn.id_factura')
                   // ->where('pt.id_medidor','=',$c->id_medidor)
                    ->where('c.estado','=','1')
                    ->where('f.periodo','=',$mes)
                     ->where('f.ano','=',$anno)
                    ->select('fn.id as id_facturacion','c.id as id_credito','c.*','fn.*')->get();
                   // dd($facturacion_total);

                }else{

                    return view('reporte.reporte-mensual');
              //  dd($facturacion_total);
                }

            }else if($reporte==3){  //en reporte 1 y 2e
                $anno = date('yy');
                //dd('reporte 3');
                $dato = array();
                $formato = array();
                if(!empty($_REQUEST['mes'])){
                     if(!empty($_REQUEST['mes'])){
                        
                        $medidores = DB::table('medidor as m')
                        ->join('punto_agua as pt','pt.id_medidor','=','m.id')
                        ->join('cliente as c','c.id','=','pt.id_cliente')
                        ->join('nivel as n','n.id','=','c.id_nivel')
                        ->where('m.estado','=','1')
                        ->select('*','m.id as id_medidor','c.id as codigo_usuario')->get();

                      //  dd($medidores);

                        foreach ($medidores as $key => $value) {
                            $id_medidor = $value->id_medidor;
                            $mes = $_REQUEST['mes'];
                            $mes1 = '';
                            $mes2 = '';
                            $mes3 = '';
                            $mes4 = '';
                            for ($i=1; $i <=4 ; $i++) { //generar facturas desde el mes que selecciono
                                
                                if($mes>=1 && $mes<=9){
                                    $mes = '0'.$mes;
                                }else if($mes==13){
                                    $mes = 1;
                                    $anno = $anno + 1;
                                }

                                // dd($mes);
                                $facturacion_total = DB::table('facturacion as fn')
                                    ->join('factura as f','f.id','=','fn.id_factura')
                                    ->join('cliente as c','c.id','=','fn.id_cliente')
                                    ->join('precio as p','p.id','=','fn.id_precio')
                                    ->join('nivel as n','n.id','=','c.id_nivel')
                                    //->where('f.estado','=','1')
                                    ->where('f.periodo','=',$mes)
                                    ->where('f.ano','=',$anno)
                                    ->where('fn.id_medidor','=',$id_medidor)
                                    ->select(
                                        'fn.id as id_facturacion',
                                        'fn.*',
                                        'f.*',
                                        'c.*',
                                        'p.*',
                                        'n.*'
                                    )->get()->last();

                                  //  dump($id_medidor);

                                    //llenar campos
                                   // dd($facturacion_total);
                                    if(count($facturacion_total)>0){
                                        if($i==1){
                                            $mes1 =  $facturacion_total->lectura;
                                            //dd($mes1);
                                        }else if($i==2){
                                            $mes2 =  $facturacion_total->lectura; 
                                        }else if($i==3){
                                            $mes3 =  $facturacion_total->lectura;
                                            
                                        }else if($i==4){
                                            $mes4 =  $facturacion_total->lectura;
                                            
                                        }
                                    }
                                    
                                
                                    //dump($mes);
                                $mes = $mes + 1;
                                
                            }

                            


                            if(count($facturacion_total)>0){
                                // dump($facturacion_total);
                                 $dato['codigo_medidor'] = $facturacion_total->id_medidor;
                                 $dato['codigo_usuario'] = $facturacion_total->id_cliente;
                                 $dato['nombre'] = $facturacion_total->nombre;
                                 $dato['primer_apellido'] = $facturacion_total->primer_apellido;
                                 $dato['segundo_apellido'] = $facturacion_total->segundo_apellido;
                                 $dato['nivel'] = $facturacion_total->id_nivel;
                                 $dato['lectura_anterior'] = '';    
                             }else{
                                 $dato['codigo_medidor'] = $id_medidor;
                                 $dato['codigo_usuario'] = $value->codigo_usuario;
                                 $dato['nombre'] = $value->nombre;
                                 $dato['primer_apellido'] = $value->primer_apellido;
                                 $dato['segundo_apellido'] = $value->segundo_apellido;
                                 $dato['nivel'] = $value->id_nivel;
                                 $dato['lectura'] = '';
                             }
                             $dato['mes1'] = $mes1;
                             $dato['mes2'] = $mes2;
                             $dato['mes3'] = $mes3;
                             $dato['mes4'] = $mes4;
                             array_push($formato, $dato);

                        }

                      }

                      
                      

                }
              // dd($formato);

            }elseif ($reporte==4) {

                if(!empty($_REQUEST['mes'])){
                    $anno = $_REQUEST['anno'];
                    $id_medidor = $_REQUEST['id_medidor'];
                    if(!empty($_REQUEST['mes'])){
                        $mes = $_REQUEST['mes'];
                        if ($mes=='Mes') {
                            return redirect('app/reportes?reporte=4');
                        } else {
                            if($mes==0){
                            $anno = $anno - 1;
                            $mes = 12;
                        }
                        if($mes>=1 && $mes<=9){
                            $mes = '0'.$mes;
                        }
                        }


                    }

                      //dd($_REQUEST['id_medidor']);
                    if(empty($_REQUEST['id_medidor'])){//si el id es nulo o no manda nada retorna los valores lista normal
                        //otros cobros
                       $facturas = DB::table('facturacion as fn')
                      ->join('factura as f','f.id','=','fn.id_factura')
                      ->join('cliente as c','c.id','=','fn.id_cliente')
                      //->where('f.estado','=','1')
                      ->where('f.periodo','=',$mes)
                      ->where('f.ano','=',$anno)
                      ->select('f.*','fn.id as id_facturacion','fn.id_medidor')
                      ->get();

                      $credito = DB::table('punto_agua as pt')
                        ->join('credito as c','c.id_punto_agua','=','pt.id')
                        ->where('pt.id_medidor','=',$id_medidor)
                        ->where('c.estado','=','1')
                        ->select('*')->get()->last();
                      // dd($credito);
                      return redirect('app/reportes?reporte=4');
                    }else{//si hay un id en la query string
                       //factura actual
                      // $clientes_f = Cliente::all();

                        $consumo = array();
                        $dato = array();
                        $graficas = array();
                        $dato['otros_cobros'] = 0;
                        $credito = DB::table('punto_agua as pt')
                        ->join('credito as c','c.id_punto_agua','=','pt.id')
                        ->where('pt.id_medidor','=',$id_medidor)
                        ->where('c.estado','=','1')
                        ->select('*')->get();
                        //dd($credito);
                            if(count($credito)>0){
                                $dato['otros_cobros'] = $credito[0]->valor_cuota;
                            }else{
                            $dato['otros_cobros'] = 0;
                            }

                        $facturacion = DB::table('facturacion as fn')
                        ->join('factura as f','f.id','=','fn.id_factura')
                        ->join('medidor as m','m.id','=','fn.id_medidor')
                        ->where('fn.id_medidor','=',$id_medidor)
                        ->where('f.periodo','=',$mes)
                        ->where('f.ano','=',$anno)
                        ->select('fn.id as id_facturacion','fn.*','f.*')->get();

                        //dd($facturacion);
                            if (count($facturacion)>0) {
                                $id_cliente = $facturacion[0]->id_cliente;
                                $id_factura = $facturacion[0]->id_factura;
                            }else{
                                return redirect('app/reportes?reporte=4');
                            }

                         $periodo_anterior = $mes -1;
                         if ($periodo_anterior>=1  && $periodo_anterior<=9) {
                             $periodo_anterior = '0'.$periodo_anterior;
                         }else if($periodo_anterior==0){
                            $periodo_anterior = 12;
                         }

                         //dd($periodo_anterior);
                        //facturas anteriores


                          $facturacion_anterior= DB::table('facturacion as fn')
                          ->join('factura as f','f.id','=','fn.id_factura')
                          ->join('medidor as m','m.id','=','fn.id_medidor')
                          ->where('fn.id_medidor','=',$id_medidor)
                         ->where('f.estado','=','1')
                          ->select('fn.id as id_facturacion','fn.*','f.*')->get();

                        if (count($facturacion_anterior)==0) {


                                $facturacion_anterior= DB::table('facturacion as fn')
                                ->join('factura as f','f.id','=','fn.id_factura')
                                ->join('medidor as m','m.id','=','fn.id_medidor')
                                ->where('fn.id_medidor','=',$id_medidor)
                                ->where('f.periodo','=',$mes)
                                ->select('fn.id as id_facturacion','fn.*','f.*')->get();
                                //return redirect('app/reportes?reporte=4');
                                //dd($facturacion_anterior);


                        }
                        $consumo_anterior = 0;
                        $acumulador = 0;
                        $id_ultima_facturacion = $facturacion[0]->id_facturacion;

                        foreach ($facturacion_anterior as $key => $value) {
                            $id_factura_anterior = $value->id_facturacion;
                            if($id_factura_anterior <$id_ultima_facturacion){
                                $acumulador = $acumulador + $value->total_pagar;
                            }
                        }

                        $dato['saldo_anterior'] = $acumulador;

                        $nivel = DB::table('cliente as c')
                        ->join('nivel as n','n.id','=','c.id_nivel')
                        ->where('c.id','=',$facturacion[0]->id_cliente)
                        ->select('*')->get();


                        $dato['nombre'] = $nivel[0]->nombre;
                        $dato['primer_apellido'] = $nivel[0]->primer_apellido;
                        $dato['segundo_apellido'] = $nivel[0]->segundo_apellido;
                        $dato['documento'] = $nivel[0]->documento;


                        $id_temp = 0;
                        $mes_actual = $mes;
                        $anno_actual = $anno;
                        $mes_inicio_grafica = $mes_actual - 5;
                        if($mes_inicio_grafica<=0){
                            $mes_inicio_grafica = 12 + ($mes_inicio_grafica);
                            $anno_actual = $anno_actual - 1;
                        }
                        //dd($mes_inicio_grafica);
                        //consumos meses anteriores
                        $grafica_objeto = [];
                        $grafica_completa = [];
                        //dd($grafica_objeto);
                        for($i = 1; $i<=6;$i++){
                            if($mes_inicio_grafica>=0 && $mes_inicio_grafica<=9){$mes_inicio_grafica = '0'.$mes_inicio_grafica;}

                            $sql_grafica = DB::table('facturacion as fn')
                            ->join('factura as f','f.id','=','fn.id_factura')
                            ->join('cliente as c','c.id','=','fn.id_cliente')
                            ->where('f.periodo','=',$mes_inicio_grafica)
                            ->where('f.ano','=',$anno_actual)
                            ->where('fn.id_medidor','=',$id_medidor)
                            ->select('f.consumo')->get();

                           // dd($mes_inicio_grafica);

                            $grafica_objeto['id_medidor'] = $id_medidor;
                            $grafica_objeto['periodo'] = $mes_inicio_grafica;
                            $grafica_objeto['anno'] = $anno_actual;
                            $grafica_objeto['id_grafica'] = $facturacion[0]->id_facturacion;
                            $grafica_objeto['id_facturacion'] = $facturacion[0]->id_facturacion;

                            if(count($sql_grafica)==0){
                                $grafica_objeto['consumo'] = 0;

                            }else{
                                $grafica_objeto['consumo'] = $sql_grafica[0]->consumo;
                            }
                            array_push($grafica_completa, $grafica_objeto);
                            $mes_inicio_grafica = $mes_inicio_grafica + 1;
                            if($mes_inicio_grafica>=13){
                                $mes_inicio_grafica = 1;
                                $anno_actual = $anno_actual + 1;
                            }
                             array_push($graficas, $grafica_objeto);

                        }


                        $medidor =  DB::table('punto_agua as pt')
                            ->join('cliente as cl','cl.id','=','pt.id_cliente')
                            ->join('medidor as md','md.id','=','pt.id_medidor')
                            ->join('facturacion as fn','fn.id_medidor','=','md.id')
                            ->join('nivel as n','n.id','=','cl.id_nivel')
                            ->where('fn.id_factura','=',$id_factura)
                          //  ->where('cl.id_nivel','=',3)
                            ->select('md.lectura_inicial as li','cl.id_nivel','md.id','pt.zona','fn.id_medidor','fn.id_cliente')->get();


                        //
                        $precio = Precio::all()->toArray();
                        $dato['precio_metro'] = $precio[0]['precio_metro'];
                        $dato['cargo_fijo'] = $precio[0]['cargo_fijo'];



                        $dato['id_cliente'] = $id_cliente;

                        if(count($facturacion)==0){
                           // $dato['lectura'] = $medidor[0]['lectura_inicial'];
                        }

                        if(count($facturacion)>=1){
                            //facturacion actual
                            $dato['consumo'] = $facturacion[0]->consumo;
                            $dato['lectura_actual'] = $facturacion[0]->lectura;
                            //facturacion anterior
                            //Cantidad facturacion == 1 se obtiene la lectura inicial del la tabla medidor
                            $mes_ultima_facturacion = $facturacion[0]->periodo;
                            $mes_facturacion_anterior = $mes_ultima_facturacion - 1;
                            $anno = date('yy');

                            if($mes_facturacion_anterior <=0){
                               $sql_facturacion_anterior = 12;
                               $anno = $anno - 1;
                            }

                            if($mes_facturacion_anterior>=0 && $mes_facturacion_anterior<=9){$mes_facturacion_anterior = '0'.$mes_facturacion_anterior;}

                            $sql_facturacion_anterior = DB::table('facturacion as fn')
                            ->join('factura as f','f.id','=','fn.id_factura')
                            ->join('cliente as c','c.id','=','fn.id_cliente')
                            ->where('f.periodo','=',$mes_facturacion_anterior)
                            ->where('f.ano','=',$anno)
                            ->where('fn.id_medidor','=',$id_medidor)
                            ->select('f.lectura')->get();
                            //dd($sql_facturacion_anterior[0]->lectura);
                            if (empty($sql_facturacion_anterior[0]->lectura)) {
                                $lectura_inicial = DB::table('medidor as md')
                                ->where('md.id','=',$id_medidor)
                                ->select('*')->get();
                                $dato['lectura_anterior'] = $lectura_inicial[0]->lectura_inicial;
                                //dd($lectura_inicial[0]->lectura_inicial);
                            }else {
                               $dato['lectura_anterior'] = $sql_facturacion_anterior[0]->lectura;
                            }   //$lectura =  $medidor[0]->li;

                                $dato['id_facturacion'] = $facturacion[0]->id_facturacion;
                                $dato['lectura_actual'] = $facturacion[0]->lectura;
                                $dato['consumo'] = $facturacion[0]->consumo;
                                $dato['fecha_lectura'] = $facturacion[0]->fecha_factura;
                                $dato['fecha_limite'] = $facturacion[0]->fecha_limite;
                                $dato['id_factura'] = $facturacion[0]->id_factura;
                                $dato['fecha_facturacion'] = $facturacion[0]->fecha_facturacion;
                                $dato['codigo_medidor'] =  $facturacion[0]->id_medidor;
                                $dato['zona'] =  $medidor[0]->zona;
                                $dato['id_nivel'] =   $nivel[0]->tipo;
                                $dato['periodo'] = $facturacion[0]->periodo;
                                $dato['ano'] = $facturacion[0]->ano;
                                $dato['metros_adicionales'] = 0;
                                $dato['precio_normal'] = 0;
                                $dato['valor_basico'] = 0;
                                $dato['valor_consumo'] = 0;
                                $dato['valor_'] = 0;
                                $dato['subsidio_cargo_fijo'] = 0;
                                $dato['valor_factura_actual'] = $facturacion[0]->total_pagar;
                                $cargo_fijo = 0;


                                if($dato['consumo']>11){
                                    // dd($dato);
                                    $dato['metros_adicionales'] = $dato['consumo'] - 11;
                                    $dato['precio_normal'] = $dato['metros_adicionales'] * $dato['precio_metro'];
                                    $dato['valor_basico'] = 11 * $dato['precio_metro'];
                                    $dato['valor_consumo'] = $dato['valor_basico'] + $dato['precio_normal'];
                                }else{
                                    $dato['valor_basico'] = $dato['consumo'] * $dato['precio_metro'];
                                    $dato['valor_consumo'] = $dato['valor_basico'];
                                    $precio['precio_normal'] = 0;
                                }
                                    $dato['subsidio_consumo'] = ($dato['valor_basico'] * $nivel[0]->porcentaje) / 100;
                                    $valor_pagar_sub = $dato['valor_basico'] - $dato['subsidio_consumo'];
                                    //me havisa hola omme esta ..?



                                // $subsidio_cons_tempo = $dato['valor_consumo'] - $valor_consumo_sub;

                                    //cargo fijo
                                    if($nivel[0]->tipo==11 || $nivel[0]->tipo==12){
                                    //  dd($nivel[0]->porcentaje);
                                    }
                                    $cargo_fijo = $precio[0]['cargo_fijo'];
                                    $subsidio_cargo = ($cargo_fijo * $nivel[0]->porcentaje) / 100;//30/110/0
                                    $subsidio_pagar_cargo = $cargo_fijo - $subsidio_cargo;///pagar   $/////00 7.800



                                    //dd($subsidio_cargo);
                                    //dd($valor_consumo);
                                //  $total = $subsidio_pagar + $subsidio_cons_t + $mts_precio_normal;
                                    $dato['subsidio_cargo'] = $subsidio_cargo;
                                    $dato['total_temporal'] = $valor_pagar_sub + $dato['precio_normal'] + $subsidio_pagar_cargo;


                              }


                              array_push($consumo, $dato);

                  }

                 //dd($dato);
                //  dd($consumo);
                  //dd($graficas);
                //    return view('reportes.reporte.factura', compact('consumo','graficas'));
                }
                //dd('no entro');
            }else if($reporte==5){
                if(!empty($_REQUEST['fecha'])){

                     $fecha = $_REQUEST['fecha'];
                     $data = [];

                     $facturacion_total = DB::table('facturacion as fn')
                      ->join('factura as f','f.id','=','fn.id_factura')
                      ->join('cliente as c','c.id','=','fn.id_cliente')
                      ->join('precio as p','p.id','=','fn.id_precio')
                      ->join('nivel as n','n.id','=','c.id_nivel')

                      ->where('f.estado','=','2')
                      ->where('f.fecha_pago','=',$fecha)
                      ->select(
                          'fn.id as id_facturacion',
                          'fn.*',
                          'f.*',
                          'c.*',
                          'p.*',
                          'n.*'
                      )->get();


                   /*   $facturacion_total = DB::table('facturacion as fn')
                      ->join('factura as f','f.id','=','fn.id_factura')
                      ->join('cliente as c','c.id','=','fn.id_cliente')
                      ->join('precio as p','p.id','=','fn.id_precio')
                      ->where('f.estado','=','2')
                      ->where('f.fecha_pago','=',$fecha)
                      ->select(
                          'fn.id as id_facturacion',
                          'fn.*',
                          'f.*',
                          'p.*'
                      )->get();

                      foreach ($facturacion_total as $key => $v) {//id_cliente,nombre,prim_ap, segun_ape,id_ni
                        //datos de la factura
                          $objeto = array(
                            'periodo'=>$v->periodo,
                            'id_factura'=>$v->id_factura,
                            'id_medidor'=>$v->id_medidor,
                            'ano'=>$v->ano,
                            'precio_metro'=>$v->precio_metro
                          );
                          dd($facturacion_total);
                          array_push($data, $objeto);
                      }
                      dd($data);*/
                      
                      


                      
                      

                      $creditos = DB::table('punto_agua as pt')
                        ->join('credito as c','c.id_punto_agua','=','pt.id')
                        ->join('facturacion as fn','fn.id_medidor','=','pt.id_medidor')
                        //->where('pt.id_medidor','=',$id_medidor)
                        ->where('c.fecha_pago','=',$fecha)
                        ->select('fn.id as id_facturacion','c.id as id_credito','c.*','fn.*','pt.id_medidor as medidor')->get();

                        //dd($creditos);

                      
                        


                    $total_pagos_dia = count($facturacion_total);
                    $dato['tota_pagos'] = $total_pagos_dia;

                      return view('reporte.reporte-diario', compact('facturacion_total','creditos','dato','fecha'));
                }else{
                  return view('reporte.reporte-diario');
                }

            }//en reporte 5



            if($reporte==1){
               return view('reporte.reporte-mensual',compact('facturacion_total','creditos'));
             }else if($reporte==2){
                return view('reporte.subsidios-x-metros',compact('facturacion_total','creditos'));
             }elseif($reporte==3){
                //dd('reporte 3');
                 return view('reporte.formato-lecturas',compact('formato','medidores'));
             }elseif ($reporte==4) {
                //dd('hasta aca');
                return view('reporte.reporte-facturas', compact('consumo','graficas'));
             }





          /*  if($numero_reporte==1){

                 return view('reporte.reporte-mensual',compact('consumo','facturacion_total'));
            }else if($numero_reporte==2){
                 return view('reporte.subsidios-x-metros',compact('consumo','facturacion_total'));
            }else if($numero_reporte==3){
                return view('reporte.formato-lecturas',compact('consumo','facturacion_total'));
            }else if($numero_reporte==4){
                return view('reporte.recibo',compact('consumo','facturacion_total'));
            }
            else if($numero_reporte==5){
                return view('reporte.facturas',compact('consumo','facturacion_total'));
            }

            */

            }

            //formato de lecturas




        //formato lecturas



        return view('reporte.index');
    }


    public function obtener_susbsidios_niveles()
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

        return view('informe.index',compact('consumo','facturacion_total'));


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
        if(!empty($_REQUEST['buscar'])){
             $clientes = new ClienteController();
            $clientes = $clientes->get_clientes_documento($_REQUEST['buscar']);
        }else{

            $clientes = new ClienteController();
            $clientes = $clientes->get_clientes();

        }



        $clientes_all = Cliente::all();
        $array_g = array();
        foreach ($clientes_all as  $v) {
            $id_cliente = $v->id;
            $facturas_pendientes = DB::table('facturacion as fn')
            ->join('factura as f','f.id','fn.id_factura')
            ->join('cliente as c','c.id','=','fn.id_cliente')
            ->where('f.estado','=','1')->where('c.id','=',$id_cliente)->select('c.id')->get();
           //
            $array_d['cantidad'] = count($facturas_pendientes);
            $array_d['id_cliente'] = $id_cliente;
            array_push($array_g, $array_d);

        }

        return view('pago.create',compact('clientes','array_g'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factura = Factura::find($request->id_factura);
        $factura->valor = $request->valor;
        $factura->estado = '2';
        $factura->save();
        Session::flash('success','¡!Pago realizado con exito!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nivel  $nivel
     * @return \Illuminate\Http\Response
     */
    public function show($id_cliente)
    {
        //v1 = metros consumo
        $data_g = array();
        $facturacion = DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->join('precio as p','p.id','=','fn.id_precio')
        ->join('cliente as c','c.id','=','fn.id_cliente')
        ->join('nivel as n','n.id','=','c.id_nivel')
        ->where('fn.id_cliente','=',$id_cliente)
        ->where('f.estado','=','1')
        ->select('*','f.id as id_factura')->get();

        if($facturacion){//ya tiene una factura anteriorñ
            $medidor = DB::table('cliente as c')
                ->join('medidor as m','m.id','=','c.id_medidor')
                ->where('c.id','=',$id_cliente)->select('m.lectura_inicial as li')->get();

            if(count($facturacion)==1){//tiene una sola facturacion entonces se toma lectura anterior la inicial del contador
                $lectura_ini = $medidor[0]->li;
                $lectura_ultima = $facturacion[0]->lectura;
                //
                $v1 = $lectura_ultima - $lectura_ini;
                $v2 = $v1 * $facturacion[0]->precio_metro;
                $v3 = $v2 / $facturacion[0]->porcentaje;
                $v4 = $facturacion[0]->cargo_fijo;
                $v5 = $facturacion[0]->subsidio;
                $v6 = $v3 + $v5;
                 $v7 = $facturacion[0]->id_factura;
                $data_u = array('id_factura'=>$v7,'mts_consumo'=>$v1,'val_consumo'=>$v2,'sub_consumo'=>$v3, 'cargo_fijo'=>$v4,'sub_cargo_fijo'=>$v5,'total'=>$v6);
                array_push($data_g, $data_u);
               // dd($data_u);
            }
        }
        //salto de la lectura inicial contador y tiene mas facturas asociadoas 2 o mas
        $val_ante = 0;
        if(count($facturacion)==2){
            $val_ante = $medidor[0]->li;
        }

        if(count($facturacion)>=2){
            foreach ($facturacion as $fc) {
               // dd('22');
                $v1 = $fc->lectura - $val_ante;
                $v2 = $v1 * $facturacion[0]->precio_metro;
                $v3 = $v2 / $facturacion[0]->porcentaje;
                $v4 = $facturacion[0]->cargo_fijo;
                $v5 = $facturacion[0]->subsidio;
                $v6 = $v3 + $v5;
                $v7 = $fc->id_factura;
                $data_u = array('id_factura'=>$v7,'mts_consumo'=>$v1,'val_consumo'=>$v2,'sub_consumo'=>$v3, 'cargo_fijo'=>$v4,'sub_cargo_fijo'=>$v5,'total'=>$v6);
                $val_ante = $fc->lectura;
                array_push($data_g, $data_u);
            }
        }


        return view('pago.list_pagos',compact('data_g'));
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
