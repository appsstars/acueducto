@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-header">
                    Reportes
                </div>
                <div class="card-body">
                    <h5 class="card-title"><small>Modulo de administración de reportes</small></h5>
                     <a href="{{url('app')}}" class="btn btn-outline-danger">VOlVER</a>
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
