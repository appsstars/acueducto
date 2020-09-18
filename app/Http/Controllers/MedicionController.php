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

class MedicionController extends Controller
{

    public function generar_factura(){
        //clientes
      date_default_timezone_set('America/Bogota');



        // $nivel = Nivel::where('id','=',$clientes[0]->id_nivel)->select('*')->get()->toArray();
        $precio = Precio::where('estado','=','2')->select('*')->get()->toArray();

        $mes = $_REQUEST['mes'];
        $anno = date('yy');
          if($mes==0){
              $anno = $anno - 1;
              $mes = 12;
          }

         // dd($mes);


        $facturacion_total = DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->join('cliente as c','c.id','=','fn.id_cliente')
        ->where('f.estado','=','1')
        //->where('f.periodo','=','03')
        ->where('f.periodo','=',$mes)
        ->where('f.ano','=',$anno)
        ->select('fn.id_cliente','fn.otros','fn.id_medidor','f.id as id_factura','c.id','c.nombre','c.nombre','c.primer_apellido','c.segundo_apellido','c.documento','f.periodo','f.ano')->orderBy('fn.id','ASC')->get();


        //dd($facturacion_total);

    // dd($facturacion_total);

        $clientes_f = Cliente::all();
        $consumo = array();
        $dato = array();


        $dato['otros_cobros'] = 0;
        $graficas = array();


        foreach ($facturacion_total as $c) {

           $id_cliente = $c->id_cliente;
           $id_factura = $c->id_factura;
           $id_medidor = $c->id_medidor;





            //otros cobros



            $credito = DB::table('punto_agua as pt')
                ->join('credito as c','c.id_punto_agua','=','pt.id')
                ->where('pt.id_medidor','=',$id_medidor)
                ->where('c.estado','=','1')
                ->select('*')->get();
            if(count($credito)>0){
                $dato['otros_cobros'] = $credito[0]->valor_cuota;
            }else{
              $dato['otros_cobros'] = 0;
            }



            //dd(count($credito));


          //  $dato['otros_cobros'] = $c->otros;

            //ultima factura del medidor - >cliente
           $facturacion = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_medidor','=',$id_medidor)
            ->where('fn.id_cliente','=',$id_cliente)
           ->where('f.estado','=','1')
            ->select('fn.id as id_facturacion','fn.*','f.*')->get()->last();
            $dato['id_facturacion'] = $facturacion->id_facturacion;





            $facturacion_anterior= DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_medidor','=',$id_medidor)
           ->where('f.estado','=','1')
            ->select('fn.id as id_facturacion','fn.*','f.*')->get();


             $cantidad_facturas = count($facturacion_anterior);
             
             
            $dato['cantidad_facturas'] = $cantidad_facturas;




            $consumo_anterior = 0;
            $acumulador = 0;
            $id_ultima_facturacion = $facturacion->id_facturacion;

            foreach ($facturacion_anterior as $key => $value) {
                
                $id_factura_anterior = $value->id_facturacion;
                if($id_factura_anterior <$id_ultima_facturacion){
                    $acumulador = $acumulador + $value->total_pagar;
                }
            }
            $dato['saldo_anterior'] = $acumulador;


            ///
            //nivel del cliente
            $nivel = DB::table('cliente as c')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->where('c.id','=',$id_cliente)
            ->select('*')->get();


            $dato['nombre'] = $nivel[0]->nombre;
            $dato['primer_apellido'] = $nivel[0]->primer_apellido;
            $dato['segundo_apellido'] = $nivel[0]->segundo_apellido;
            $dato['documento'] = $nivel[0]->documento;


            $id_temp = 0;
            $mes_actual = date('m');
            $anno_actual = date('yy');
            $mes_inicio_grafica = $mes_actual - 6;
            if($mes_inicio_grafica<=0){
                $mes_inicio_grafica = 12 + ($mes_inicio_grafica);
                $anno_actual = $anno_actual - 1;
            }

            //consumos meses anteriores
            $grafica_objeto = [];
            $grafica_completa = [];
            //dd($grafica_objeto);
            for($i = 0; $i<=6;$i++){
                if($mes_inicio_grafica>=0 && $mes_inicio_grafica<=9){$mes_inicio_grafica = '0'.$mes_inicio_grafica;}
                $sql_grafica = DB::table('facturacion as fn')
                ->join('factura as f','f.id','=','fn.id_factura')
                ->join('cliente as c','c.id','=','fn.id_cliente')
                ->where('f.periodo','=',$mes_inicio_grafica)
                ->where('f.ano','=',$anno_actual)
                ->where('fn.id_medidor','=',$id_medidor)
                ->select('f.consumo')->get();



                $grafica_objeto['id_medidor'] = $id_medidor;
                $grafica_objeto['periodo'] = $mes_inicio_grafica;
                $grafica_objeto['anno'] = $anno_actual;
                $grafica_objeto['id_grafica'] = $c->id;
                $grafica_objeto['id_facturacion'] = $dato['id_facturacion'];

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
            //dd($graficas);

           // $graficas['id_facturacion'] = $dato['id_facturacion'];



           //dd($graficas);
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



//aca en la anteriors sql yo lo que hacia era traer todas las facturas y luego empezaba a mirar y calcular el consumo
//pero como ya se simplifico a un attr en la db yo creeria que esta parte se suspende un poco cual
////
//dd($facturacion);m




            if(count($facturacion)==0){
               // $dato['lectura'] = $medidor[0]['lectura_inicial'];
            }



            if(count($facturacion)>=1){
                //facturacion actual
                $dato['consumo'] = $facturacion->consumo;
                $dato['lectura_actual'] = $facturacion->lectura;
                //facturacion anterior
                //Cantidad facturacion == 1 se obtiene la lectura inicial del la tabla medidor
                $mes_ultima_facturacion = $facturacion->periodo;
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

                
                if(count($sql_facturacion_anterior)==0){
                    $lectura_medidor = Medidor::where('id','=',$id_medidor)->select('*')->get()->last();
                     $dato['lectura_anterior'] = $lectura_medidor->lectura_inicial;
                }else{
                    $dato['lectura_anterior'] = $sql_facturacion_anterior[0]->lectura; 
                }
                //$lectura =  $medidor[0]->li;
               
                $dato['lectura_actual'] = $facturacion->lectura;
                $dato['consumo'] = $facturacion->consumo;
                $dato['fecha_lectura'] = $facturacion->fecha_factura;
                $dato['fecha_limite'] = $facturacion->fecha_limite;
                $dato['id_factura'] = $facturacion->id_factura;
                $dato['fecha_facturacion'] = $facturacion->fecha_facturacion;
                $dato['codigo_medidor'] =  $facturacion->id_medidor;
                $dato['zona'] =  $medidor[0]->zona;
                $dato['id_nivel'] =   $nivel[0]->tipo;
                $dato['periodo'] = $facturacion->periodo;
                $dato['ano'] = $facturacion->ano;
                $dato['metros_adicionales'] = 0;
                $dato['precio_normal'] = 0;
                $dato['valor_basico'] = 0;
                $dato['valor_consumo'] = 0;
                $dato['valor_'] = 0;
                $dato['subsidio_cargo_fijo'] = 0;
                $dato['valor_factura_actual'] = $facturacion->total_pagar;
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

         // dd($graficas); de hecho creo que si lo copiio todo sirve cierto ??
        return view('medicion.recibos', compact('consumo','graficas'));
    }


    public function index()
    {

      //duplicamos code ?? toca mk
      if(empty($_REQUEST['id_medidor'])){//si el id es nulo o no manda nada retorna los valores lista normal

            //dd($_REQUEST['mes']);
            //otros cobros
            if(!empty($_REQUEST['mes'])){//
               // dd('aca');
                $mes = $_REQUEST['mes'];
                        if($mes==0){

                            $mes = 12;
                        }
                        if($mes>=1 && $mes<=9){
                            $mes = '0'.$mes;
                        }
                $facturas = DB::table('facturacion as fn')
                ->join('factura as f','f.id','=','fn.id_factura')
                ->join('cliente as c','c.id','=','fn.id_cliente')
                //->where('f.estado','=','1')
                ->where('f.periodo','=',$mes)
                ->select('f.*','fn.id as id_facturacion','fn.id_medidor')
                ->get();
                $credito = DB::table('credito as cr')
                ->join('punto_agua as pt','pt.id','=','cr.id_punto_agua')
                ->where('cr.estado','=','1')
                ->select('cr.id as id_credito','cr.valor','cr.estado','cr.valor_cuota','pt.*')->get();
                   // dd($facturas);

                    //   $credito = DB::table('punto_agua as pt')
                    //     ->join('credito as c','c.id_punto_agua','=','pt.id')
                    //    // ->join('facturacion as fn','fn.id_cliente','=','pt.id_cliente')
                    //     //->join('factura as f','f.id','=','fn.id_factura')
                    //    // ->where('pt.id_medidor','=',$id_medidor)
                    //     ->where('c.estado','=','1')
                    //     ->select('pt.*','c.valor')->get();
                        //dd($credito);
                return view('medicion.index', compact('facturas','credito'));
            }else{
                return view('medicion.index', compact('facturas','credito'));
            }

      }else{//si hay un id en la query string
        //factura actual
      // $clientes_f = Cliente::all();
        // $mes = $_REQUEST['mes'];
        // if($mes==0){

        //     $mes = 12;
        // }
        // if($mes>=1 && $mes<=9){
        //     $mes = '0'.$mes;
        // }
        // dd($mes);
        $consumo = array();
        $dato = array();

        $graficas = array();

        $dato['otros_cobros'] = 0;
        //dd($_REQUEST['id_medidor']);
        $id_medidor =$_REQUEST['id_medidor'];
        $mes = $_REQUEST['periodo'];
        $credito = DB::table('punto_agua as pt')
        ->join('credito as c','c.id_punto_agua','=','pt.id')
        ->where('pt.id_medidor','=',$id_medidor)
        ->where('c.estado','=','1')
        ->select('*')->get();
            if(count($credito)>0){
                $dato['otros_cobros'] = $credito[0]->valor_cuota;
            }else{
              $dato['otros_cobros'] = 0;
            }


        $facturacion = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_medidor','=',$_REQUEST['id_medidor'])
            //->where('f.estado','=','1')
            ->where('f.periodo','=',$mes)
            ->select('fn.id as id_facturacion','fn.*','f.*')->get()->last();
           // dd($facturacion);

           $id_cliente = $facturacion->id_cliente;
           $id_factura = $facturacion->id_factura;
           $id_medidor = $facturacion->id_medidor;





            //facturas anteriores
             $facturacion_anterior= DB::table('facturacion as fn')
              ->join('factura as f','f.id','=','fn.id_factura')
              ->join('medidor as m','m.id','=','fn.id_medidor')
              ->where('fn.id_medidor','=',$_REQUEST['id_medidor'])
             ->where('f.estado','=','1')
              ->select('fn.id as id_facturacion','fn.*','f.*')->get();


              // facturas pendientes
              //cantidad facturas pendientes
               $facturas_pendientes_usuario = DB::table('facturacion as fn')
                ->join('factura as f','f.id','=','fn.id_factura')
                ->join('medidor as m','m.id','=','fn.id_medidor')
                ->where('fn.id_medidor','=',$_REQUEST['id_medidor'])
               ->where('f.estado','=','1')
                ->select('fn.id as id_facturacion','fn.*','f.*')->get();


             $cantidad_facturas = count($facturas_pendientes_usuario);
             
             
            $dato['cantidad_facturas'] = $cantidad_facturas;



              //


            $consumo_anterior = 0;
            $acumulador = 0;
            $id_ultima_facturacion = $facturacion->id_facturacion;

            foreach ($facturacion_anterior as $key => $value) {
                $id_factura_anterior = $value->id_facturacion;
                if($id_factura_anterior <$id_ultima_facturacion){
                    $acumulador = $acumulador + $value->total_pagar;
                }
            }

            $dato['saldo_anterior'] = $acumulador;

            $nivel = DB::table('cliente as c')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->where('c.id','=',$facturacion->id_cliente)
            ->select('*')->get();


            $dato['nombre'] = $nivel[0]->nombre;
            $dato['primer_apellido'] = $nivel[0]->primer_apellido;
            $dato['segundo_apellido'] = $nivel[0]->segundo_apellido;
            $dato['documento'] = $nivel[0]->documento;


            $id_temp = 0;
            $mes_actual = $mes;
            $anno_actual = date('yy');
            $mes_inicio_grafica = $mes_actual - 5;
            if($mes_inicio_grafica<=0){
                $mes_inicio_grafica = 12 + ($mes_inicio_grafica);
                $anno_actual = $anno_actual - 1;
            }

            //consumos meses anteriores
            $grafica_objeto = [];
            $grafica_completa = [];
            //dd($grafica_objeto);
            for($i = 0; $i<=6;$i++){
                if($mes_inicio_grafica>=0 && $mes_inicio_grafica<=9){$mes_inicio_grafica = '0'.$mes_inicio_grafica;}
                $sql_grafica = DB::table('facturacion as fn')
                ->join('factura as f','f.id','=','fn.id_factura')
                ->join('cliente as c','c.id','=','fn.id_cliente')
                ->where('f.periodo','=',$mes_inicio_grafica)
                ->where('f.ano','=',$anno_actual)
                ->where('fn.id_medidor','=',$_REQUEST['id_medidor'])
                ->select('f.consumo')->get();



                $grafica_objeto['id_medidor'] = $id_medidor;
                $grafica_objeto['periodo'] = $mes_inicio_grafica;
                $grafica_objeto['anno'] = $anno_actual;
                $grafica_objeto['id_grafica'] = $facturacion->id_facturacion;
                $grafica_objeto['id_facturacion'] = $facturacion->id_facturacion;

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



//aca en la anteriors sql yo lo que hacia era traer todas las facturas y luego empezaba a mirar y calcular el consumo
//pero como ya se simplifico a un attr en la db yo creeria que esta parte se suspende un poco cual
////
//dd($facturacion);m
         
            


           



            if(!empty($facturacion)){
                //facturacion actual
                $dato['consumo'] = $facturacion->consumo;
                $dato['lectura_actual'] = $facturacion->lectura;
                //facturacion anterior
                //Cantidad facturacion == 1 se obtiene la lectura inicial del la tabla medidor
                $mes_ultima_facturacion = $facturacion->periodo;
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
                ->select('f.lectura')->get()->toArray();
               //dd($sql_facturacion_anterior[0]->lectura);
                if (empty($sql_facturacion_anterior)) {

                $lectura_inicial = DB::table('medidor as md')
                ->where('md.id','=',$id_medidor)
                ->select('*')->get();
                //dd($lectura_inicial[0]->lectura_inicial);
                $dato['lectura_anterior'] = $lectura_inicial[0]->lectura_inicial;
                    //dd($lectura_inicial[0]->lectura_inicial);
                }else {
                    //dd('ni modo');
                $dato['lectura_anterior'] = $sql_facturacion_anterior[0]->lectura;
                }

                //$lectura =  $medidor[0]->li;
                // $dato['lectura_anterior'] = $sql_facturacion_anterior[0]->lectura;
                $dato['id_facturacion'] = $facturacion->id_facturacion;
                $dato['lectura_actual'] = $facturacion->lectura;
                $dato['consumo'] = $facturacion->consumo;
                $dato['fecha_lectura'] = $facturacion->fecha_factura;
                $dato['fecha_limite'] = $facturacion->fecha_limite;
                $dato['id_factura'] = $facturacion->id_factura;
                $dato['fecha_facturacion'] = $facturacion->fecha_facturacion;
                $dato['codigo_medidor'] =  $facturacion->id_medidor;
                $dato['zona'] =  $medidor[0]->zona;
                $dato['id_nivel'] =   $nivel[0]->tipo;
                $dato['periodo'] = $facturacion->periodo;
                $dato['ano'] = $facturacion->ano;
                $dato['metros_adicionales'] = 0;
                $dato['precio_normal'] = 0;
                $dato['valor_basico'] = 0;
                $dato['valor_consumo'] = 0;
                $dato['valor_'] = 0;
                $dato['subsidio_cargo_fijo'] = 0;
                $dato['valor_factura_actual'] = $facturacion->total_pagar;
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
     //dd($consumo);

       return view('factura.recibo-usuario', compact('consumo','graficas'));
    }

    public function cantidad_facturas(){
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
        return $array_g;
    }






    /**

     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $clientes = new ClienteController();
        $clientes = $clientes->get_clientes();

//        dd($clientes);

      /* $mediciones = DB::table('cliente as c')
            ->join('facturacion as fn','fn.id_cliente','=','c.id')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->select('c.nombre','c.primer_apellido','c.id as id_cliente','f.lectura','f.fecha_factura','fn.fecha_limite')->get();
           // dd($mediciones);*/

            if(!empty($_REQUEST['buscar'])){
              $medidores = DB::table('medidor')
              ->where('id','LIKE','%'.$_REQUEST['buscar'].'%')
              ->select('*')->get();
            }else{
               $medidores = DB::table('punto_agua as pt')
               ->join('medidor as m','m.id','=','pt.id_medidor')
               ->where('pt.estado','=','1')
               ->select('*')->orderBy('m.id','asc')->get();
             
            }

           //dd($medidores);



            $dato = array();
            $lecturas = array();
            foreach ($medidores as $key => $value) {
             $id_medidor = $value->id;
             $id_cliente = $value->id_cliente;
             //ultima medicion
             $facturacion = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
             ->join('cliente as c','c.id','=','fn.id_cliente')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->join('punto_agua as pt','pt.id_medidor','fn.id_medidor')
            ->where('fn.id_medidor','=',$id_medidor)
            ->where('fn.id_cliente','=',$id_cliente)
            ->where('pt.estado','1')
            ->select('fn.id as id_facturacion','fn.*','f.*','c.*')->get()->last();





            if(empty($facturacion)){
                //llenar datos cliente medidor
                $cliente_medidor = DB::table('punto_agua as pt')
                ->join('cliente as c','c.id','=','pt.id_cliente')
                ->where('pt.id_cliente','=',$id_cliente)
                ->where('pt.estado','=','1')
                ->select('*')->get()->last();


                //
              $facturacion_temporal = DB::table('facturacion as fn')
                ->join('factura as f','f.id','=','fn.id_factura')
                 ->join('cliente as c','c.id','=','fn.id_cliente')
                ->join('medidor as m','m.id','=','fn.id_medidor')
                ->join('punto_agua as pt','pt.id_medidor','fn.id_medidor')
                ->where('fn.id_medidor','=',$id_medidor)
                ->where('pt.estado','1')
                ->select('fn.id as id_facturacion','fn.*','f.*','c.*')->get()->last();

                





                
            }

             $nivel = DB::table('cliente as c')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->where('c.id','=',$id_cliente)
            ->select('*')->get();

           // dd($facturacion);


            //sql para verificar si el medidor tiene facturas anteriores si la facturacion con el cliente llego en vacio
             $facturacion_anterior = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
             ->join('cliente as c','c.id','=','fn.id_cliente')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_medidor','=',$id_medidor)
            ->select('fn.id as id_facturacion','fn.*','f.*','c.*')->get();



            //

            
            $factura_anterior = count($facturacion_anterior) - 2;



            if(!empty($facturacion_anterior[$factura_anterior]->lectura)){//
              $dato['lectura_anterior'] = $facturacion_anterior[$factura_anterior]->lectura;
            }else{
                //
                if(count($facturacion_anterior)==1){
                   $lectura_medidor = Medidor::where('id','=',$id_medidor)->select('*')->get()->last();
                    $dato['lectura_anterior'] = $lectura_medidor->lectura_inicial;
                }
                
            }

           


            $precio = Precio::all()->toArray();
            $dato['precio_metro'] = $precio[0]['precio_metro'];
            $dato['cargo_fijo'] = $precio[0]['cargo_fijo'];






           // dd($facturacion);



            //
            if(empty($facturacion->nombre)){ 
                $dato['nombre'] = $cliente_medidor->nombre;
                $dato['primer_apellido'] = $cliente_medidor->primer_apellido;
            }else{ 
                $dato['nombre'] = $facturacion->nombre;
                $dato['primer_apellido'] = $facturacion->primer_apellido;
            }

            
            
            //consumo
            if(empty($facturacion->consumo)){$dato['consumo']=0;}else{$dato['consumo'] = $facturacion->consumo;}
            //totalpagar
            if(empty($facturacion->total_pagar)){$dato['total_pagar']=0;}else{$dato['total_pagar'] = $facturacion->total_pagar;}
            //
            if(empty($facturacion->id_medidor)){ $dato['id_medidor'] = $id_medidor; }else{ $dato['id_medidor'] = $facturacion->id_medidor; }

            if(empty($facturacion->documento)){$dato['documento'] = '';}else{ $dato['documento'] = $facturacion->documento;}
            
             
              
              //lectura
            if(empty($facturacion)){
                if( empty($facturacion_temporal)){//medidor es nuevo
                    //medidor nuevo sin facturaciones anteriores
                    $lectura_medidor = Medidor::where('id','=',$id_medidor)->select('*')->get()->last();
                    $dato['lectura'] = $lectura_medidor->lectura_inicial;
                    
                }else{//medidor es usado pero con nuevo cliente
                    $lectura_medidor = DB::table('facturacion as fn')
                     ->join('factura as f','f.id','=','fn.id_factura')
                     ->where('fn.id_medidor','=',$id_medidor)
                     ->select('*')->get()->last();
                     $dato['lectura'] = $lectura_medidor->lectura;
                }
            }else{//este tiene una facturacion con el cliente activo
                $dato['lectura'] = $facturacion->lectura;
                

            }
                    
                
            
              //fecha_factura
              if(empty($facturacion->fecha_factura)){$dato['fecha_factura']='--/--/--';}else{$dato['fecha_factura'] = $facturacion->fecha_factura;}
              //fecha limite
              if(empty($facturacion->fecha_limite)){$dato['fecha_limite']='--/--/--';}else{$dato['fecha_limite'] = $facturacion->fecha_limite;}


               if(empty($facturacion->id_cliente)){ $dato['id_cliente'] = $id_cliente; }else{$dato['id_cliente'] = $facturacion->id_cliente;}
               //id_factura
               if(empty($facturacion->id_factura)){$dato['id_factura']='null';}else{$dato['id_factura'] = $facturacion->id_factura;}
               




              if($dato['consumo']>11){
                // dd($dato);
                 $dato['metros_adicionales'] = $dato['consumo'] - 11;
                 $dato['precio_normal'] = $dato['metros_adicionales'] * $dato['precio_metro'];
                 $dato['valor_basico'] = 11 * $dato['precio_metro'];
                 $dato['valor_consumo'] = $dato['valor_basico'] + $dato['precio_normal'];
             }else{
                  $dato['valor_basico'] = $dato['consumo'] * $dato['precio_metro'];
                  $dato['valor_consumo'] = $dato['valor_basico'];
                  $dato['precio_normal'] = 0;
             }
               $dato['subsidio_consumo'] = ($dato['valor_basico'] * $nivel[0]->porcentaje) / 100;
               $total_consumo = $dato['valor_basico'] - $dato['subsidio_consumo'];
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
                  if($dato['id_factura']=='null'){
                    $dato['total_temporal'] = 0;
                  }else{
                    $dato['total_temporal'] = $total_consumo + $dato['precio_normal'] + $subsidio_pagar_cargo;
                  }
                  





            array_push($lecturas, $dato);


            }



/*





            //facturas anteriores
             $facturacion_anterior= DB::table('facturacion as fn')
              ->join('factura as f','f.id','=','fn.id_factura')
              ->join('medidor as m','m.id','=','fn.id_medidor')
              ->where('fn.id_medidor','=',$_REQUEST['id_medidor'])
             ->where('f.estado','=','1')
              ->select('fn.id as id_facturacion','fn.*','f.*')->get();


            $consumo_anterior = 0;
            $acumulador = 0;
            $id_ultima_facturacion = $facturacion->id_facturacion;

            foreach ($facturacion_anterior as $key => $value) {
                $id_factura_anterior = $value->id_facturacion;
                if($id_factura_anterior <$id_ultima_facturacion){
                    $acumulador = $acumulador + $value->total_pagar;
                }
            }*/




        return view('medicion.create',compact('lecturas'));

        //dd($mediciones,$clientes);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacturaRequest $request)
    {
        date_default_timezone_set('America/Bogota');
        //verificar facturas en el mismo mes
        $id_medidor = $request->id_medidor;
        $mes_actual = date('m');
         //$mes_actual = '03';
        $anno_actual = date('yy');

        //cliente 

        $cliente = DB::table('punto_agua as pt')
        ->join('cliente as c','c.id','=','pt.id_cliente')
        ->where('pt.estado','=','1')
        ->where('pt.id_medidor','=',$id_medidor)
        ->select('*')->get()->last();

        

        $facturas_mes = DB::table('facturacion as fn')
        ->join('factura as f','f.id','fn.id_factura')
       // ->where('f.ano','=',$anno_actual)
       // ->where('f.periodo','=',$mes_actual)
        ->where('fn.id_medidor','=',$id_medidor)///id_cliebte
         ->where('fn.id_cliente','=',$cliente->id_cliente)///id_cliebte
        ->select('*')->get()->last();

        

        

        if(count($facturas_mes)>0){
            $ultima_fecha = $facturas_mes->created_at;
            $fecha_actual = date('Y-m-d H:i:s');

            //
            $dias = (strtotime($ultima_fecha)-strtotime($fecha_actual))/86400;
            $dias = abs($dias); $dias = floor($dias);
            
            if($dias<=25){
                Session::flash('alert','Este medidor ya tiene lecturas asignadas en este mes.');
                return redirect()->back();

            }

            //
            
        }


        $id_credito = null;


        //

        $ultima_medicion = DB::table('facturacion as fn')
        ->join('factura as f','f.id','=','fn.id_factura')
        ->where('fn.id_medidor','=',$request->id_medidor)
        ->select('f.lectura','fn.id_cliente')->get()->last();

         
        //dd($ultima_medicion);

        //verificar lecturas en tabla facturacion o lectura inicial

        if(empty($ultima_medicion)){
            $ultima_medicion = DB::table('punto_agua as pt')
            ->join('cliente as c','c.id','=','pt.id_cliente')
            ->join('medidor as m','m.id','=','pt.id_medidor')
            ->where('pt.id_medidor','=',$request->id_medidor)
            ->select('pt.*','m.*','c.*')->get()->last();

            $consumo = $request->lectura - $ultima_medicion->lectura_inicial;
            $id_cliente = $ultima_medicion->id_cliente;
        }else{
            $consumo = $request->lectura - $ultima_medicion->lectura;
            $id_cliente = $ultima_medicion->id_cliente;
        }

       
        //dd($consumo);


        //$ul_medicion = (int)$ultima_medicion[1];

         //dd($ulti,_medicion);

        if(!empty($ultima_medicion->lectura)){
            if($request->lectura < $ultima_medicion->lectura){
                Session::flash('alert','La lectura no puede ser inferior a al ultimo registro.');
                return redirect()->back();
            }
        }
            


        //creditos otros cobros
        $otros_cobros = DB::table('punto_agua as pt')
        ->join('credito as c','c.id_punto_agua','=','pt.id')
        ->where('pt.id_medidor','=',$request->id_medidor)
        ->where('c.estado','=','1')
        ->select('*')->get()->last();

        //dd($otros_cobros);

        if(count($otros_cobros)>0){
            $id_credito = $otros_cobros->id;
        }
        Session::put('fecha_lectura',$request->fecha_factura);



        $factura = new Factura();
        $periodo = substr($request->fecha_factura,5,-3);
        $anno = substr($request->fecha_factura,0,-6);
        //$dia = substr($request->fecha_factura.' ', -3,-1);

        $fecha_facturacion = $anno.'-'.$periodo.'-27';
        $request['fecha_facturacion'] = $fecha_facturacion;

        if($periodo==12){
            $periodo = '01';
            $anno = $anno + 1;
        }elseif($periodo<9){
            $periodo = $periodo; $periodo = ''.$periodo;
        }else{
            $periodo = $periodo;
        }
        $request['fecha_limite'] = strtotime($request->fecha_factura  ."+ 10 days");
        $request['fecha_limite'] = date("d-m-Y",$request['fecha_limite']);
        //dd($request->all());
       // dd(date("d-m-Y",$request['fecha_limite']));
        //TOtal pgar

      $nivel = DB::table('cliente as c')
      ->join('nivel as n','n.id','=','c.id_nivel')
      ->where('c.id','=',$id_cliente)
      ->select('*')->get();

      $precio = Precio::where('estado','2')->select('*')->get();
      $precio_metro = $precio[0]['precio_metro'];
      $cargo_fijo =  $precio[0]['cargo_fijo'];

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



      //dd($subsidio_cargo);
      //dd($valor_consumo);
  //  $total = $subsidio_pagar + $subsidio_cons_t + $mts_precio_normal;


      $total_temporal = $valor_pagar_sub + $precio_normal + $subsidio_pagar_cargo;      //


        $factura->lectura = $request->lectura;
        $factura->periodo = $periodo;
        $factura->fecha_factura = $request->fecha_factura;
        $factura->estado = $request->estado;
        $factura->ano = $anno;
        $factura->consumo = $consumo;
        $factura->total_pagar = $total_temporal;
        $factura->save();

        $id_factura = $factura->id;
        $request['id_credito'] = $id_credito;

        $facturacion = new FacturacionController();
        $facturacion = $facturacion->store($request,$id_factura);

        if($facturacion){
           Session::flash('success','Registro creador correctamente');
        }

        return redirect()->back();



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_medidor)
    {
      //$lectura = Factu
        $facturacion = DB::table('facturacion as fn')
        ->where('fn.id_medidor','=',$id_medidor)->select('*')->get();
        $factura_anterior = count($facturacion) - 2;

        $id_cliente = $facturacion[$factura_anterior]->id_cliente;

        $id_factura_anterior = $facturacion[$factura_anterior]->id_factura;
        $factura_anterior = Factura::find($id_factura_anterior);

        $lectura_anterior = $factura_anterior->lectura;

        $lectura_form = $request->lectura;

        if($lectura_form >= $lectura_anterior){
          $consumo = $lectura_form - $lectura_anterior;
             $factura = Factura::find($request->id_factura);
             //dd($request->all());
              $periodo = substr($request->fecha_factura,5,-3);
              $anno = substr($request->fecha_factura,0,-6);
              //$dia = substr($request->fecha_factura.' ', -3,-1);

              $fecha_facturacion = $anno.'-'.$periodo.'-27';
              $request['fecha_facturacion'] = $fecha_facturacion;

              if($periodo==12){
                  $periodo = '01';
                  $anno = $anno + 1;
              }elseif($periodo<9){
                  $periodo = $periodo; $periodo = ''.$periodo;
              }else{
                  $periodo = $periodo;
              }
              $request['fecha_limite'] = strtotime($request->fecha_factura  ."+ 10 days");
              $request['fecha_limite'] = date("d-m-Y",$request['fecha_limite']);
              //dd($request->all());
             // dd(date("d-m-Y",$request['fecha_limite']));
              //TOtal pgar

            $nivel = DB::table('cliente as c')
            ->join('nivel as n','n.id','=','c.id_nivel')
            ->where('c.id','=',$id_cliente)
            ->select('*')->get();

            $precio = Precio::where('estado','2')->select('*')->get();
            $precio_metro = $precio[0]['precio_metro'];
            $cargo_fijo =  $precio[0]['cargo_fijo'];

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



            //dd($subsidio_cargo);
            //dd($valor_consumo);
        //  $total = $subsidio_pagar + $subsidio_cons_t + $mts_precio_normal;


            $total_temporal = $valor_pagar_sub + $precio_normal + $subsidio_pagar_cargo;      //
            //dd();


              $factura->lectura = $request->lectura;
              $factura->periodo = $periodo;
              $factura->fecha_factura = $request->fecha_factura;


              $factura->consumo = $consumo;
              $factura->total_pagar = $total_temporal;;
              $factura->save();







              if($facturacion){
                 Session::flash('success','Registro actualizado con exito...');
              }

              return redirect()->back();
        }else{
           Session::flash('alert','La lectura no puede ser inferior a al ultimo registro.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factura  $factura
     * @return \Illuminate\Http\Response
     */

    public function listar_lecturas($id_medidor){
             $facturacion = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_medidor','=',$id_medidor)
            //->where('f.estado','=','1')
            ->select('fn.id as id_facturacion','fn.*','f.*','f.id as id_lectura')->get();

            return response()->json($facturacion);
    }



    public function destroy($id_lectura)
    {
        $facturacion = DB::table('facturacion as fn')->where('id_factura','=',$id_lectura)->select('id')->get()->last();
        $facturacion_id = Facturacion::find($facturacion->id);
        $facturacion_id->delete();

        //factura
        $factura = Factura::find($id_lectura);
        $factura->delete();
        return response()->json(['Mensaje'=>'ELiminado correctamente']);
    }
}
