@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="container">

	<div class="row justify-content-center">
		<div class="col-12 col-md-12">
             @if (session('update_medidor'))
                </div >
                    <p class="alert alert-success">
                        {{session('update_medidor')}}
                    </p>
                </div>
             @endif
             <div class="row align-items-center justify-content-center" >
                <div class="formulario" style="width:700px" >
                    <form  method="POST" action="{{route('medidor.update',$medidor->id)}}" autocomplete="off">
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
                            <button type="submit" class="btn btn-outline-success btn-sm">Subir</button>
                        </div>
                    </div>

                        <div class="bg-primary pd-1" style="border-radius: 5px;color: #fff"><h4 class="text-center">Editar Medidor</h4></div>
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="name">Marca del Medidor</label>
                            <input type="text" class="form-control text-capitalize" name="marca"  value='{{$medidor->marca}}' required>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="apellido">#Serial</label>
                            <input type="text" class="form-control text-capitalize" name="serial"  value='{{$medidor->serial}}' required>
                            </div>
                            <div class="form-group col-md-4">
                            <label for="">Predio</label>
                            <input type="hidden" name="id_punto" value="{{$punto->id}}">
                            <input type="text" class="form-control text-capitalize" name="zona"  value='{{$punto->zona}}' required>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection
