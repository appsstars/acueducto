<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factura;
use App\Http\Requests\FacturaRequest;
use Session;
use DB;
use App\Cliente;
use App\Precio;
use PDF;
use App\Nivel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $medidores = DB::table('medidor')->select('*')->get();
        foreach ($medidores as $key => $m) {
            $facturacion = DB::table('facturacion as fn')
            ->join('factura as f','f.id','=','fn.id_factura')
            ->join('medidor as m','m.id','=','fn.id_medidor')
            ->where('fn.id_medidor','=',$m->id)
            ->select('fn.id as id_facturacion','fn.*','f.*')->get();
            //

            foreach ($facturacion as $key => $f) {
                $id_cliente = $f->id_cliente;
                //
                    $nivel = DB::table('cliente as c')
                    ->join('nivel as n','n.id','=','c.id_nivel')
                    ->where('c.id','=',$id_cliente)
                    ->select('*')->get();

                        $precio = Precio::where('estado','2')->select('*')->get();
                        $precio_metro = $precio[0]['precio_metro'];
                        $cargo_fijo =  $precio[0]['cargo_fijo'];



                     //consumo
                    $consumo = $f->consumo;
                    if($f->consumo>11){
                        // dd($dato);
                         $metros_adicionales = $f->consumo - 11;
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


                    $total_temporal = $valor_pagar_sub + $precio_normal + $subsidio_pagar_cargo;

                    $factura = Factura::find($f->id_factura);
                    $factura->total_pagar = round($total_temporal,-2);
                    $factura->save();
            }
        }

}







    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $factura = array(
  array('id' => '1','lectura' => '88','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '2','lectura' => '359','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '3','lectura' => '178','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '4','lectura' => '508','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '5','lectura' => '104','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '6','lectura' => '281','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '7','lectura' => '192','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '8','lectura' => '532','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '9','lectura' => '35','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '10','lectura' => '21','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '11','lectura' => '489','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '12','lectura' => '103','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '13','lectura' => '340','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '14','lectura' => '302','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '15','lectura' => '228','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '16','lectura' => '728','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '17','lectura' => '406','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '18','lectura' => '174','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '19','lectura' => '414','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '20','lectura' => '748','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '21','lectura' => '507','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '22','lectura' => '772','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '23','lectura' => '209','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '24','lectura' => '35','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '25','lectura' => '409','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '26','lectura' => '751','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '27','lectura' => '1184','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '28','lectura' => '874','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '29','lectura' => '556','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '30','lectura' => '132','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '31','lectura' => '187','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '32','lectura' => '790','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '33','lectura' => '752','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '34','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:51','updated_at' => '2020-02-07 04:45:51'),
  array('id' => '35','lectura' => '512','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '36','lectura' => '564','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '37','lectura' => '385','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '38','lectura' => '256','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '39','lectura' => '62','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '40','lectura' => '91','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '41','lectura' => '258','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '42','lectura' => '734','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '43','lectura' => '343','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '44','lectura' => '113','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '45','lectura' => '276','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '46','lectura' => '43','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '47','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '48','lectura' => '1598','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '49','lectura' => '156','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '50','lectura' => '764','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '51','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '52','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '53','lectura' => '356','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '54','lectura' => '352','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '55','lectura' => '105','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '56','lectura' => '757','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '57','lectura' => '769','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '58','lectura' => '884','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '59','lectura' => '262','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '60','lectura' => '299','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '61','lectura' => '399','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '62','lectura' => '298','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '63','lectura' => '25','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '64','lectura' => '328','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '65','lectura' => '91','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '66','lectura' => '380','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '67','lectura' => '373','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '68','lectura' => '834','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '69','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '70','lectura' => '45','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '71','lectura' => '211','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '72','lectura' => '104','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '73','lectura' => '182','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '74','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '75','lectura' => '416','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '76','lectura' => '315','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '77','lectura' => '1137','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '78','lectura' => '380','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '79','lectura' => '38','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '80','lectura' => '77','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:52','updated_at' => '2020-02-07 04:45:52'),
  array('id' => '81','lectura' => '686','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '82','lectura' => '73','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '83','lectura' => '795','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '84','lectura' => '41','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '85','lectura' => '182','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '86','lectura' => '175','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '87','lectura' => '98','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '88','lectura' => '446','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '89','lectura' => '138','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '90','lectura' => '821','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '91','lectura' => '865','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '92','lectura' => '384','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '93','lectura' => '745','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '94','lectura' => '1013','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '95','lectura' => '406','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '96','lectura' => '413','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '97','lectura' => '537','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '98','lectura' => '183','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '99','lectura' => '170','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '100','lectura' => '624','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '101','lectura' => '185','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '102','lectura' => '540','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '103','lectura' => '514','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '104','lectura' => '677','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '105','lectura' => '291','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '106','lectura' => '142','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '107','lectura' => '212','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '108','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '109','lectura' => '368','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '110','lectura' => '515','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '111','lectura' => '8','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '112','lectura' => '262','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '113','lectura' => '115','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '114','lectura' => '401','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '115','lectura' => '929','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '116','lectura' => '422','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '117','lectura' => '440','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '118','lectura' => '2485','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '119','lectura' => '449','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '120','lectura' => '1615','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '121','lectura' => '329','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '122','lectura' => '306','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '123','lectura' => '524','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '124','lectura' => '180','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '125','lectura' => '199','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '126','lectura' => '392','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:53','updated_at' => '2020-02-07 04:45:53'),
  array('id' => '127','lectura' => '301','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '128','lectura' => '329','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '129','lectura' => '494','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '130','lectura' => '823','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '131','lectura' => '1110','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '132','lectura' => '424','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '133','lectura' => '67','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '134','lectura' => '1067','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '135','lectura' => '802','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '136','lectura' => '762','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '137','lectura' => '94','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '138','lectura' => '590','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '139','lectura' => '17','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '140','lectura' => '236','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '141','lectura' => '162','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '142','lectura' => '1150','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '143','lectura' => '149','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '144','lectura' => '113','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '145','lectura' => '497','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '146','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '147','lectura' => '1536','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '148','lectura' => '21','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '149','lectura' => '476','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '150','lectura' => '429','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '151','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '152','lectura' => '26','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '153','lectura' => '123','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '154','lectura' => '281','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '155','lectura' => '298','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '156','lectura' => '1192','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '157','lectura' => '263','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '158','lectura' => '18','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '159','lectura' => '199','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '160','lectura' => '630','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '161','lectura' => '520','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '162','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '163','lectura' => '67','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '164','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '165','lectura' => '381','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '166','lectura' => '32','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '167','lectura' => '535','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '168','lectura' => '203','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '169','lectura' => '371','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '170','lectura' => '310','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:54','updated_at' => '2020-02-07 04:45:54'),
  array('id' => '171','lectura' => '430','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '172','lectura' => '292','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '173','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '174','lectura' => '345','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '175','lectura' => '144','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '176','lectura' => '305','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '177','lectura' => '351','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '178','lectura' => '84','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '179','lectura' => '322','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '180','lectura' => '425','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '181','lectura' => '414','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '182','lectura' => '68','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '183','lectura' => '159','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '184','lectura' => '305','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '185','lectura' => '410','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '186','lectura' => '467','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '187','lectura' => '552','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '188','lectura' => '137','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '189','lectura' => '378','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '190','lectura' => '514','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '191','lectura' => '494','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '192','lectura' => '181','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '193','lectura' => '47','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '194','lectura' => '43','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '195','lectura' => '195','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '196','lectura' => '6','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '197','lectura' => '680','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '198','lectura' => '775','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '199','lectura' => '15','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '200','lectura' => '805','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '201','lectura' => '136','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '202','lectura' => '92','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '203','lectura' => '351','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '204','lectura' => '594','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '205','lectura' => '191','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '206','lectura' => '126','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '207','lectura' => '790','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '208','lectura' => '98','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '209','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '210','lectura' => '380','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '211','lectura' => '517','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '212','lectura' => '80','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '213','lectura' => '637','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '214','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '215','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '216','lectura' => '457','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '217','lectura' => '301','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:55','updated_at' => '2020-02-07 04:45:55'),
  array('id' => '218','lectura' => '606','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '219','lectura' => '52','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '220','lectura' => '380','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '221','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '222','lectura' => '229','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '223','lectura' => '562','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '224','lectura' => '22','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '225','lectura' => '179','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '226','lectura' => '19','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '227','lectura' => '425','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '228','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '229','lectura' => '217','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '230','lectura' => '330','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '231','lectura' => '351','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '232','lectura' => '119','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '233','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '234','lectura' => '282','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '235','lectura' => '869','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '236','lectura' => '744','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '237','lectura' => '183','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '238','lectura' => '417','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '239','lectura' => '61','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '240','lectura' => '39','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '241','lectura' => '131','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '242','lectura' => '357','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '243','lectura' => '365','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '244','lectura' => '259','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '245','lectura' => '101','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '246','lectura' => '446','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '247','lectura' => '27','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '248','lectura' => '496','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '249','lectura' => '9','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '250','lectura' => '558','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '251','lectura' => '514','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '252','lectura' => '717','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '253','lectura' => '278','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '254','lectura' => '492','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '255','lectura' => '1162','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '256','lectura' => '506','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '257','lectura' => '473','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '258','lectura' => '481','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '259','lectura' => '567','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '260','lectura' => '241','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '261','lectura' => '627','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '262','lectura' => '492','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '263','lectura' => '356','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:56','updated_at' => '2020-02-07 04:45:56'),
  array('id' => '264','lectura' => '484','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '265','lectura' => '2768','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '266','lectura' => '644','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '267','lectura' => '50','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '268','lectura' => '64','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '269','lectura' => '112','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '270','lectura' => '172','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '271','lectura' => '12','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '272','lectura' => '215','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '273','lectura' => '192','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '274','lectura' => '152','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '275','lectura' => '0','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57'),
  array('id' => '276','lectura' => '135','periodo' => '01','fecha_factura' => '2020-01-15','ano' => '2020','estado' => '1','valor' => NULL,'created_at' => '2020-02-07 04:45:57','updated_at' => '2020-02-07 04:45:57')
);
set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        foreach ($factura as $key => $value) {
            $id_factura = $value['id'];

            $fact = Factura::find($id_factura);
            $fact->updated_at = $value['created_at'];
            $fact->save();

        }
        dd('success');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
