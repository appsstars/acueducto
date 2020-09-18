<?php

namespace App\Http\Controllers;

use App\Cliente;
use DB;
use App\Medidor;
use Illuminate\Http\Request;
use App\Http\Requests\MedidorRequest;
use App\PuntoAgua;
use Illuminate\Contracts\Session\Session as SessionSession;
use Session;

class MedidorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
    public function store($request)
    {
        //dd('nuevo medidor');
        $medidor = new Medidor();
        $medidor->marca = $request->marca;
        $medidor->serial = $request->serial;
        $medidor->lectura_inicial = $request->lectura_inicial;
         $medidor->estado = '1';
        $medidor->save();
        $id_medidor = $medidor->id;
        return $id_medidor;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Medidor  $medidor
     * @return \Illuminate\Http\Response
     */
    public function show(Medidor $medidor)
    {
        dd('llegamossss');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Medidor  $medidor
     * @return \Illuminate\Http\Response
     */
    public function edit($id_medidor)
    {
        $puntos = DB::table('punto_agua as pt')
        ->where('pt.id_medidor','=',$id_medidor)
        ->select('*')->get()->toArray();
        $id_punto = $puntos[0]->id;
        $id_medidor = $puntos[0]->id_medidor;
        $id_cliente = $puntos[0]->id_cliente;
        //dd($id_cliente);
         Session::put('back',$id_cliente);
         $punto = PuntoAgua::find($id_punto);
        $medidor =  Medidor::find($id_medidor);
        //dd($medidor);

        return view('puntos.medidor.edit', compact('medidor','punto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medidor  $medidor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_medidor)
    {
       
        //dd($id_medidor);
        if (!empty($request->id_punto)) {//este condicional es para actualizar los datos del medidor.
            $medidores = Medidor::find($id_medidor);
            $medidores->marca = $request->marca;
            $medidores->serial = $request->serial;
            $medidores->save();
            //
            $punto = PuntoAgua::find($request->id_punto);
            $punto->zona = $request->zona;
            $punto->save();
            return back()->with('update_medidor', 'Medidor Actualizado');
        } elseif (!empty($id_medidor)) {//este condicional es para suspender el punto, el medidor, el cliente"en caso que solo tenga un solo punto".
            //dd($id_medidor);
            //consultamos el punto de agua y verificamos el id del cliente y el id del punto de agua
            $puntos = DB::table('punto_agua as pt')
            ->where('pt.id_medidor','=',$id_medidor)
            ->where('pt.estado','=','1')
            ->select('pt.id_cliente','pt.id as id_punto')->get()->toArray();
            $id_cliente = $puntos[0]->id_cliente;
            $id_punto = $puntos[0]->id_punto;
            //verificamos la cantidad de puntos que tiene el cliente.
            $punto_agua = DB::table('punto_agua as pt')
            ->where('pt.id_cliente','=',$id_cliente)
            ->where('pt.estado','=','1')
            ->select('pt.*')->get()->toArray();



            $total_puntos = count($punto_agua);//hacemos un conteo de los puntos
           // dd($total_puntos);
            //
            //traemos la ultima factura de este punto de agua y verificamos si esta cancelada "estado2"
            $factura = DB::table('facturacion as fn')
            ->join('factura as fc','fc.id','=','fn.id_factura')
            ->where('fn.id_medidor','=',$id_medidor)
            ->select('fc.*')->get()->last();

            //verificar si tiene ftacturas el medidor

            // $id_factura = $factura->id;
            //dd($factura);
            if(count($factura)>0){
                $estado_factura = $factura->estado;
            }else{
                $estado_factura = null;
            }


            // dd($estado_factura);
            if ($estado_factura=='1') { // si la factura esta pendiente "estado1" no se puede suspender
                return back()->with('factura_estado', 'La FACTURA esta pendiente ponerse al dia');
                dd('factura pendiente');
            }else{//si la factura esta paga 'estado2' se suspende el punto

                if ($total_puntos==1){//si el cliente tiene un solo punto, se suspende el cliente y el punto de agua.
                    $medidores = Medidor::find($id_medidor);
                    $medidores->estado = '2';
                    $medidores->save();
                    $cliente = Cliente::find($id_cliente);
                    $cliente->estado = '2';
                    $cliente->save();
                    $puntos = PuntoAgua::find($id_punto);
                    $puntos->estado = '2';
                    $puntos->save();
                   // dd('cliente suspendido y medidor');
                    return back()->with('update_estado', 'Medidor Suspendido');

                } elseif($total_puntos >=2) { //si el cliente tiene mas de un punto solo se suspende el medidor y el punto de agua.
                    $medidores = Medidor::find($id_medidor);
                    $medidores->estado = '2';
                    $medidores->save();
                    $puntos = PuntoAgua::find($id_punto);
                    $puntos->estado = '2';
                    $puntos->save();
                   // dd('solo medidor y punto');
                    return back()->with('update_estado', 'Medidor Suspendido');

                }
            }
             
            return back()->with('update_estado', 'Medidor Suspendido');
        }
        //dd('ninguno');

        //
        //return back()->with('update_medidor', 'Medidor Actualizado');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medidor  $medidor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medidor $medidor)
    {
        //
    }
}
