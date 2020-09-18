@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="container">

	<div class="row justify-content-center">
		<div class="col-12 col-md-12">
             @if (session('update'))
                </div >
                    <p class="alert alert-success">
                        {{session('update')}}
                    </p>
                </div>
             @endif

                <div class="row align-items-center justify-content-center" >
                    <div class="formulario" style="width:700px" >
                        <form  method="POST" action="{{route('clientes.update',$cliente_edit->id)}}" autocomplete="off">
                        {{ csrf_field() }}
                        {{method_field('put')}}
                        <div class="row align-items-center justify-content-center">
                            <div class="">
                                <button class="btn btn-outline-info btn-sm"><a href="{{url('app/punto')}}/{{Session::get('back')}}/edit" >Volver</a></button>
                           </div>
                           <div style="text-align: center; width:15px">
                               <span>|</span>
                           </div>
                           <div class="">
                               <button type="submit" class="btn btn-outline-success btn-sm">Guardar</button>
                           </div>
                       </div>
                        <div class="bg-primary pd-1" style="border-radius: 5px;color: #fff"><h4 class="text-center">Editar Usuario</h4></div>
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="name">Nombre del Usuario</label>
                            <input type="text" class="form-control text-capitalize" name="nombre" placeholder="Ej: Juan" value='{{$cliente_edit->nombre}}' required>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="apellido">Primer Apellido</label>
                            <input type="text" class="form-control text-capitalize" name="primer_apellido" placeholder="Ej: Cruz" value='{{$cliente_edit->primer_apellido}}' required>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="">Segundo Apellido</label>
                            <input type="text" class="form-control text-capitalize" name="segundo_apellido" placeholder="Ej: Rojas" value='{{$cliente_edit->segundo_apellido}}' required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputAddress2">Documento</label>
                                <input type="text" class="form-control" name="documento" id="inputAddress2" placeholder="Ej: 7.77" value='{{$cliente_edit->documento}}' required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="celular">Telefono</label>
                                <input type="text" class="form-control" name="telefono" id="inputAddress" placeholder="Ej: 322....." value='{{$cliente_edit->telefono}}' required>
                            </div>

                            <div class="form-group col-md-6">
                            <label for="inputState">Nivel</label>
                            <select id="inputState" class="form-control" name="id_nivel" required>
                                <option >{{$id_nivel[0]->tipo}}</option>
                                @foreach($niveles as $n)
                                    <option value="{{$n->id}}"> {{$n->tipo}} </option>
                                @endforeach
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label for="inputAddress2">Direccion</label>
                            <input type="text" class="form-control" name="direccion" id="inputAddress2" placeholder="Ej: Cll #" value='{{$cliente_edit->direccion}}' required>
                            </div>

                        </div>
                            <hr>
                    </form>
                    </div>


                </div>
            </div>

    </div>
</div>



@endsection
