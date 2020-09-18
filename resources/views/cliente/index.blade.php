@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="container">
         <div class="justify-content-center">
            <div class="card text-center">
                <div class="justify-content-center">
                        <div class="taba">
                            <div class="col-md-12"><br>
                                <form action="">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <a href="{{ url('app') }}" class="btn btn-success btn-block btn-outline-dark btn-sm">Volver</a>
                                            </div>
                                            <div class="col-sm-12 col-md-2 col-lg-2">
                                                <a href="#form-create" data-toggle="modal" class="btn btn-success btn-block btn-outline-success btn-sm">Nuevo</a>
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-sm-5 col-md-4 col-lg-4" >
                                                @include('filtro.index')
                                            </div>
                                            <div class="col-sm-5 col-md-2 col-lg-2">
                                                <button class="btn btn-primary btn-outline-primary btn-block btn-sm">Buscar</button>
                                            </div>
                                            <div class="clearfix"></div>

                                        </div>
                                    </form>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped" id="myTable" style="display: block;width: 100%">
                                    <thead>

                                        <tr>
                                            <td style="width: 150px">#</td>
                                            <td style="width: 150px">Nombre</td>
                                            <td style="width: 150px">Primer Apellido</td>
                                            <td style="width: 150px">Segundo Apellido</td>
                                            <td style="width: 150px">Documento</td>
                                            <td style="width: 150px">Nivel</td>
                                            <td style="width: 150px">Celular</td>
                                            <td style="width: 150px">Ajustes</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clientes as $cli)
                                        <tr>
                                            <td>{{$cli->id}}</td>
                                            <td>{{$cli->nombre}}</td>
                                            <td>{{$cli->primer_apellido}}</td>
                                            <td>{{$cli->segundo_apellido}}</td>
                                            <td>{{$cli->documento}}</td>
                                            <td>{{$cli->tipo}}</td>
                                            <td>{{$cli->telefono}}</td>
                                            <td><a href="{{route('punto.edit',$cli->id)}}" class="btn btn-outline-success btn-block btn-sm">Ver</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                                
                         </div>
                </div>
            </div>
        </div> 
    </div>
</div>


@include('cliente.create')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    });
</script>

@endsection
