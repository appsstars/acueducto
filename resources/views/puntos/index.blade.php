@extends('layouts.admin')
@section('content')
<div class="container">

        <div class="row">
            <div class="col-md-10"></div>
            <div class="col-md-2">
                <button class="btn btn-outline-dark btn-sm" style="margin-left:90px">
                    <a href="{{ url('app/clientes') }}" style="color:black; ">Volver</a>
                </button>

            </div>

        </div>




        <div class="row border" >
        <div class="col-6 border" ><h3 class="text-center">Datos del Cliente</h3><br></div>
        <div class="col-6 border" ><h3 class="text-center">Puntos de agua</h3><br>
        </div>
        <div class="col-6 border" >
            <div class="col-md-3" style="margin-top:20px">
				<a class="btn  btn-outline-warning btn-sm" href="{{route('clientes.edit',$cliente_ver->id)}}">Editar</a>
			</div>
            <ul class="list-group " border-radius:2px;>
                <li class="d-inline p-2 bg-light border-bottom">Nombre: <ul>{{$cliente_ver->nombre}}</ul> </li>
                <li class="d-inline p-2 bg-white border-bottom"> Primer Apellido: <ul>{{$cliente_ver->primer_apellido}}</ul></li>
                <li class="d-inline p-2 bg-ligth border-bottom">Segundo Apellido: <ul>{{$cliente_ver->segundo_apellido}}</ul></li>
                <li class="d-inline p-2 bg-white border-bottom">Documento: <ul>{{$cliente_ver->documento}}</ul></li>
                <li class="d-inline p-2 bg-ligth border-bottom">Celular <ul>{{$cliente_ver->telefono}}</ul></li>
                <li class="d-inline p-2 bg-white border-bottom">Nivel <ul></ul></li>
            </ul>

        </div>
        <div class="col-6 border" >
            <div class="table-responsive">
                <button style="margin-left:420px; margin-top:20px" class="btn  btn-outline-success btn-sm" data-toggle="modal" data-target="#medidor">Agregar</button>
				<table class="table table-bordered table-striped" id="myTable">
					<thead>

						<tr>
                            <td>Nombre del Predio</td>
                            <td>Codigo Medidor</td>
							<td>Marca Medidor</td>
                            <td>Serial</td>
							<td colspan="2">Ajustes</td>
						</tr>
					</thead>
					<tbody>

                        @foreach ($puntos as $punto)
                            <tr>
                               <td>{{$punto->zona}}</td>
                                <td>{{$punto->id_medidor}}</td>
                                <td>{{$punto->marca}}</td>
                                <td>{{$punto->serial}}</td>
                                <td><a href="{{route('medidor.edit',$punto->id_medidor)}}" class="btn  btn-outline-warning btn-sm">Editar</a></td>
                            <td><button style="" onclick="suspender('{{$punto->id_medidor}}')" class="btn  btn-outline-dark btn-sm">Suspender</button></td>
                                {{-- <td><a href="" class="btn  btn-outline-dark btn-sm">Suspender</a></td> --}}
						    </tr>
                        @endforeach

                    </tbody>
                    <div class="row text-justify " >

                            <span style="text-center">
                                @if (session('update_estado'))
                                    </div >
                                        <p class="alert alert-primary">
                                            {{session('update_estado')}}
                                        </p>
                                    </div>
                                @elseif(session('factura_estado'))
                                    </div >
                                        <p class="alert alert-warning">
                                            {{session('factura_estado')}}
                                        </p>
                                    </div>
                                @endif
                            </span>


                    </div>
                </table>
            </div>
        </div>
        <form action="" method="POST" id="suspencion">
            {{csrf_field()}}
            {{method_field('put')}}
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger ">
                        <h5 >CONFIRMAR !</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <p>DESEA SUSPENDER ESTE PUNTO?</p>
                            <input type="hidden" name="id_medidor">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" >Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="modal-medidor">
            <form  method="POST" action="" autocomplete="off">
                {{ csrf_field() }}
                {{method_field('put')}}
                <div class="modal fade" id="medidor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="bg-primary pd-1" style="border-radius: 5px;color: #fff; padding:16px 3px 6px 0">
                            <h4 class="text-center">Editar Medidor</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="formulario">
                                {{-- {{route('medidor.update',$medidor->id)}} --}}
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <input type="radio" name="estado" onchange="llenar_form()" value="1"> Nuevo
                                            <input type="radio" name="estado" onchange="llenar_form()" value="2"> Usado
                                        </div>
                                        <div class="form-group col-md-6 parte1">
                                            <input type="hidden" name="id_cliente" value="{{$cliente_ver->id}}">
                                            <label for="name">Marca del Medidor</label>
                                            <input type="text" class="form-control text-capitalize" name="marca" >
                                            </div>
                                            <div class="form-group col-md-6 parte1">
                                            <label for="apellido">#Serial</label>
                                            <input type="text" class="form-control text-capitalize" name="serial">
                                            </div>

                                            <div class="form-group col-md-6 parte1">
                                                <label for="">Predio</label>
                                                <input type="hidden" name="id_punto" >
                                                <input type="text" class="form-control text-capitalize" name="zona">
                                           </div>

                                           <div class="form-group col-md-6 parte1">
                                                <label for="">Lectura inicial</label>
                                                <input type="hidden" name="id_punto" >
                                                <input type="text" class="form-control text-capitalize" name="lectura_inicial" >
                                            </div>

                                        <div class="col-md-12 parte2">
                                            <span>Seleccione un medidor</span>
                                            <select name="id_medidor" class="form-control">
                                                @foreach ($medidores_disponibles as $i)
                                                    <option value="{{$i->id}}"> Codigo: {{$i->id}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12 parte2">
                                            <label for="">Zona</label>
                                             <input type="text" class="form-control text-capitalize" name="zona_x" >
                                        </div>
                                    </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        </div>
                    </div>
                    </div>
                </div>
            </form>
        </div>


</div>
@if(Session::has('success'))
    <?php
        echo "<script>
            window.onload = function(){
                toastr.success('".Session::get('success')."');
            }
        </script>";
    ?>
@endif

<style>
    .parte1,.parte2{display: none}
</style>
<script>
    function suspender(id_medidor){
        var action = "{{url('app/medidor')}}/"+id_medidor;
        $('#suspencion').attr('action',action);
        $("#exampleModal").modal("show");
        //$('#exampleModal input[name=id_medidor]').val(id_medidor);
    }

    function llenar_form(){
        var el = $("input[type=radio]:checked").val();
        if(el==1){
            $('.parte1').show(); $('.parte2').hide();
        }else{
            $('.parte1').hide(); $('.parte2').show();
        }
        var id = 0;
        var action = "{{url('app/punto')}}/"+id;
        $('#modal-medidor form').attr('action',action);
    }
</script>
@endsection
