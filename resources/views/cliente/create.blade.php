<div class="modal fade" id="form-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
            @if(Session::has('success'))
            <script> window.onload = function(){$('#form-create').modal('show');} </script>
            <p class="alert alert-success"> {{Session::get('success')}} </p>
            @endif
            @if(count($errors)>0)
            <script> window.onload = function(){$('#form-create').modal('show');} </script>
                        <p class="alert alert-danger">
                            @foreach ($errors->all() as $e)
                                <span> {{$e}} </span><br>
                            @endforeach
                        </p>
                    @endif
        <form method="POST" action="{{route('clientes.store')}}" autocomplete="off">
            {{ csrf_field() }}
                <div class="bg-primary pd-1" style="border-radius: 5px;"><p class="text-center">Nuevo Usuario</p></div>
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="name">Nombre del Usuario</label>
                        <input type="text" class="form-control text-capitalize" name="nombre" placeholder="Ej: Juan" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="apellido">Primer Apellido</label>
                    <input type="text" class="form-control text-capitalize" name="primer_apellido" placeholder="Ej: Cruz" required>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="">Segundo Apellido</label>
                    <input type="text" class="form-control text-capitalize" name="segundo_apellido" placeholder="Ej: Rojas" required>
                  </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputAddress2">Documento</label>
                        <input type="text" class="form-control" name="documento" id="inputAddress2" placeholder="Ej: 7.77" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="celular">Telefono</label>
                        <input type="text" class="form-control" name="telefono" id="inputAddress" placeholder="Ej: 322....." required>
                    </div>

                  <div class="form-group col-md-6">
                    <label for="">Selecciona Nivel</label>
                    <select  class="form-control" name="id_nivel" required>
                       <option ></option>
                        @foreach($nivel as $n)
                            <option value="{{$n->id}}"> {{$n->tipo}} </option>
                       @endforeach
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputAddress2">Direccion</label>
                    <input type="text" class="form-control" name="direccion" id="inputAddress2" placeholder="Ej: Cll #" required>
                  </div>

                </div>
                <div class="">
                   <button type="submit" class="btn btn-outline-primary">Crear</button>
                   <button type="button" class="btn btn-outline-dark float-right" data-dismiss="modal">Cerrar</button>
                </div>
              </form>
        </div>
      </div>

    </div>
  </div>
</div>
