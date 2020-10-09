@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-header">
                    
                        <a href="{{url('app')}}" style="margin-right: 80% !important;"><i class="fas fa-angle-double-left icon-return"><h5 class="atras">atras</h5> </i></a>
                        <h3 style="top:15%;left:42%;position:absolute">Reportes</h3>
                    
                    
                    
                    
                </div>
                   
                <div class="card-body">
                    <h5 class="card-title"><small>Modulo de administración de reportes</small></h5>
                     <!-- <a href="{{url('app')}}" style="text-decoration: none;"><i class="fas fa-angle-double-left icon-return"> </i><h5 class="atras">atras</h5></a> -->
                    <a href="?reporte=1" class="btn btn-outline-success">REPORTE MENSUAL</a>
                    <a href="?reporte=5" class="btn btn-outline-success">REPORTE DIARIO</a>
                    <a href="?reporte=2" class="btn btn-outline-success">SUBSIDIOS POR NIVEL</a>
                    <a href="?reporte=3" class="btn btn-outline-success">FORMATO DE LECTURAS</a>
                    <a href="?reporte=4" class="btn btn-outline-success">FACTURAS</a>

                </div>
                <div class="card-footer text-muted">
                    2020
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
