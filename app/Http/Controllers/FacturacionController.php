<?php

namespace App\Http\Controllers;

use App\Facturacion;
use Illuminate\Http\Request;

class FacturacionController extends Controller
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
    public function store($request, $id_factura = null)
    {

        $precio = new PrecioController();
        $precio = $precio->get_precio();
        $id_precio = $precio[0]->id;
       // dd($request->all());


        $facturacion = new Facturacion();
        $facturacion->id_cliente = $request->id_cliente;
        $facturacion->id_factura = $id_factura;
        $facturacion->id_medidor =  $request->id_medidor;
        $facturacion->id_credito = $request->id_credito;
        $facturacion->id_precio = $id_precio;
        $facturacion->fecha_facturacion = $request->fecha_facturacion;
        $facturacion->fecha_limite = $request->fecha_limite;
        $facturacion->save();
        return true;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function show(Facturacion $facturacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Facturacion $facturacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facturacion $facturacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facturacion  $facturacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facturacion $facturacion)
    {
        //
    }
}
