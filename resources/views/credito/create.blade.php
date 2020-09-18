<div class="modal fade" id="credito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <h5 class="text-center" id="exampleModalLabel">OTROS COBROS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container">
                @if(Session::has('credito_crear'))
                <script> window.onload = function(){//$('#credito').modal('show');} </script>
                <p class="alert alert-success"> {{Session::get('credito_crear')}} </p>
                @endif
                @if(Session::has('rechazado'))
                <script> window.onload = function(){$('#credito').modal('show');} </script>
                <p class="alert alert-danger"> {{Session::get('rechazado')}} </p>
                @endif
                @if(count($errors)>0)
                <script> window.onload = function(){$('#credito').modal('show');} </script>
                    <p class="alert alert-danger">
                        @foreach ($errors->all() as $e)
                            <span> {{$e}} </span><br>
                        @endforeach
                    </p>
                @endif

                <form method="POST" action="{{route('credito.store')}}" autocomplete="off" >
                    {{ csrf_field() }}
                    <div class="row">
                    <div class="form-group col-md-12">
                        <label for="recipient-name" class="col-form-label">Valor</label>
                        <input type="hidden" name="id_punto">
                        <input type="number" class="form-control" id="valor" name="valor">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="recipient-name" class="col-form-label">Por Concepto de :</label>
                         <input type="text" class="form-control" id="descripcion" name="descripcion" value="Sin definir">
                    </div>

                    <div class="form-group col-md-6">
                    <input type="hidden" class="form-control" id="fecha" name="fecha" value="<?php echo date('yy-m-d');?>">
                    </div>
                    <div class="form-group col-md-3">
                    <input type="hidden" class="form-control" id="cuotas" name="cuotas" value="1">
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </form>
          </div>

      </div>

    </div>
  </div>
</div>
