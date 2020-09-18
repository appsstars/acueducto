@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card text-center">
                                <div class="alert alert-dismissable" role="alert">
                                    <div class="container">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <a href="{{ url('app') }}" class="btn btn-success btn-block btn-outline-dark">Volver</a>
                                                </div>
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4">
                                                    @include('filtro.index')
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary btn-outline-primary btn-block">Buscar</button>
                                                </div>
                                            </div>

                                        </form>

                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-12">
                                                <div class="tabla table-responsive">
                                                    <table class="table table-bordered table-striped" id="myTable" style="display: block;width: 100%">
                                                        <thead>

                                                            <tr>
                                                                <td style="max-width: 40px">Cod_Medidor</td>
                                                                <td>Nombre</td>
                                                                <td>Primer Apellido</td> 
                                                                <td>Segundo Apellido</td>
                                                                <td style="max-width: 40px">Documento</td>
                                                                <td>Predio</td>
                                                                <td>Telefono</td>
                                                                <td>Valor</td>
                                                                <td>Opciones</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($creditos as $cli)
                                                            <tr>
                                                                <td>{{$cli->id_medidor}}</td>
                                                                <td>{{$cli->nombre}}</td>
                                                                <td>{{$cli->primer_apellido}}</td>
                                                                <td>{{$cli->segundo_apellido}}</td>
                                                                <td>{{$cli->documento}}</td>
                                                                <td>{{$cli->zona}}</td>
                                                                <td>{{$cli->telefono}}</td>
                                                                <td style="width:2%"><button type="button" class="btn  btn-outline-success btn-sm" onclick="crear_credito('{{$cli->id_punto}}')" >Agregar Cobros</button></td>
                                                                <td><a href="{{url('app/credito/lista',$cli->id_punto)}}" class="btn  btn-outline-warning btn-sm">Detalles/Pagar</a></td>
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
                    </div>
                </div>
            </div>
    </div>                        
</div>




@include('credito.create')
<script>
    function crear_credito(id_punto){
        $('input[name=id_punto]').val(id_punto);
        $('#credito').modal('show');
    }
</script>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    });
</script>
@endsection
