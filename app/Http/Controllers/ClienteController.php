<?php

namespace App\Http\Controllers;
use DB;
use App\Nivel;
use App\Cliente;
use App\Medidor;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteRequest;
use Session;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty($_REQUEST['buscar'])){
            $clientes = new ClienteController();
            $clientes = $clientes->get_list_documentos($_REQUEST['buscar']);
            //dd($clientes);
       }else{
           $clientes = new ClienteController();
           $clientes = $clientes->get_lista()
           ;
       }

        $nivel = DB::table('nivel')->select('*')->get();
        //dd($clientes, $nivel);
        return view('cliente.index',compact("clientes",'nivel'));
    }

    public function get_clientes(){
         if(!empty($_REQUEST['buscar'])){
            $clientes = DB::table('punto_agua as pt')
            ->join('cliente as cl','cl.id','=','pt.id_cliente')
            ->join('medidor as md','md.id','=','pt.id_medidor')
            ->join('nivel as n','n.id','=','cl.id_nivel')
            ->where('cl.documento','LIKE','%'.$_REQUEST['buscar'].'%')
            ->Orwhere('pt.id_medidor','LIKE','%'.$_REQUEST['buscar'].'%')
            ->select('pt.id as id_punto','pt.id_cliente','pt.id_medidor','pt.zona','cl.nombre','cl.primer_apellido','cl.segundo_apellido','cl.documento','cl.telefono','n.tipo','md.lectura_inicial','md.marca','md.serial')->get();
         }else{
            $clientes = DB::table('punto_agua as pt')
            ->join('cliente as cl','cl.id','=','pt.id_cliente')
            ->join('medidor as md','md.id','=','pt.id_medidor')
            ->join('nivel as n','n.id','=','cl.id_nivel')
            ->select('pt.id as id_punto','pt.id_cliente','pt.id_medidor','pt.zona','cl.nombre','cl.primer_apellido','cl.segundo_apellido','cl.documento','cl.telefono','n.tipo','md.lectura_inicial','md.marca','md.serial')->get();
         }


         //dd($clientes);
        return $clientes;
    }

    public function get_clientes_documento($documento){
        //dd($documento);
        $clientes = DB::table('punto_agua as pt')
        ->join('medidor as m','m.id','=','pt.id_medidor')
        ->join('cliente as cl','cl.id','=','pt.id_cliente')
        ->where('cl.documento','LIKE','%'.$documento.'%')
        //->orwhere('m.id','=',$documento)
        ->join('nivel as n','n.id','=','cl.id_nivel')
        ->select('pt.id as id_punto','pt.id_cliente','pt.id_medidor','pt.zona','cl.nombre','cl.primer_apellido','cl.segundo_apellido','cl.documento','cl.telefono','n.tipo')->get();
        //dd($clientes);
        return $clientes;
    }

    public function get_clientes_medidor($documento){
        //dd($documento);
        $clientes = DB::table('punto_agua as pt')
        ->join('medidor as m','m.id','=','pt.id_medidor')
        ->join('cliente as cl','cl.id','=','pt.id_cliente')
        //->where('cl.documento','LIKE','%'.$documento.'%')
        ->orwhere('m.id','=',$documento)
        ->join('nivel as n','n.id','=','cl.id_nivel')
        ->select('pt.id as id_punto','pt.id_cliente','pt.id_medidor','pt.zona','cl.nombre','cl.primer_apellido','cl.segundo_apellido','cl.documento','cl.telefono','n.tipo')->get();
       // dd($clientes);
        return $clientes;
    }

    public function get_list_documentos($documento){
        $clientes = DB::table('cliente')
        ->join('nivel','nivel.id','=','cliente.id_nivel')
        ->where('cliente.documento','LIKE','%'.$documento.'%')
        ->select('cliente.*','nivel.tipo as tipo')->get();
        return $clientes;

    }

    public function get_lista(){
        $clientes = DB::table('cliente')
        ->join('nivel','nivel.id','=','cliente.id_nivel')
        ->where('cliente.estado','=','1')
        ->select('cliente.*','nivel.tipo as tipo')->get();
        //dd($clientes);
        return $clientes;

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
    public function store(ClienteRequest $request)
    {
        //dd($request->all());
        //$id_medidor = $medidor->store($request);
        //if($id_medidor){
            $cliente = new Cliente();
            $cliente->id_nivel = $request->id_nivel;
            $cliente->nombre = $request->nombre;
            $cliente->primer_apellido = $request->primer_apellido;
            $cliente->segundo_apellido = $request->segundo_apellido;
            $cliente->documento = $request->documento;
            $cliente->telefono = $request->telefono;
            $cliente->direccion = $request->direccion;
            $cliente->estado = '1';
            $cliente->save();
            $id_cliente = $cliente->id;

            // return array($id_cliente,$direccion);
           // Session::flash('success','Cliente creado con exito.');
            return redirect('app/punto/'.$id_cliente.'/edit');

        //}

    }
    /**


     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Session::put('back',$id);
        $cliente_edit = Cliente::find($id);
        $niveles = Nivel::all();
        $id_nivel = DB::table('nivel as n')
        ->where('n.id','=',$cliente_edit->id_nivel)
        ->select('*')->get();

        //dd($niveles);
        return view('cliente.edit', compact('cliente_edit','niveles','id_nivel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {

        //dd($request->all());

        $cliente = Cliente::findOrfail($cliente->id);
        $cliente->id_nivel = $request->id_nivel;
        $cliente->nombre = $request->nombre;
        $cliente->primer_apellido = $request->primer_apellido;
        $cliente->segundo_apellido = $request->segundo_apellido;
        $cliente->documento = $request->documento;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->save();
        //Session::flash('success','Cliente editado con exito.');
        return back()->with('update', 'Usuario Actualizado Correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
