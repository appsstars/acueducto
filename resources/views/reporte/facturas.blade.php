
@extends('layouts.admin')
@section('title','Informe mensual')
@section('content')
<style>
	.content{
		max-width: 80%;
		margin: auto;
		display: block;
	}
</style>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-header">
                    Reportes
                </div>
                <div class="card-body">
                	<a href="{{url('app/reportes')}}" class="btn btn-outline-info">Volver</a>
                	<a href="" class="btn btn-outline-success">Factura individual</a>
                	<a href="" class="btn btn-outline-success">Factura pendientes</a>
                	<a href="" class="btn btn-outline-success">Factura pagas</a>
                </div>
                <div class="card-footer text-muted">
                    2020
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
