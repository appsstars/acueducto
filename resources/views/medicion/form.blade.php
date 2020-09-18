<form action="{{route('medicion.store')}}" autocomplete="off" method="post">
  {{csrf_field()}}
  <div class="modal fade" id="form-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Añadir lectura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @if(count($errors)>0)
           <?php echo "<script> window.onload = function(){ toastr.error('¡Error! reviza y luego intenta nuevamente.'); $('#form-create').modal('show'); }</script>";?>
              <p class="alert alert-danger">
                  @foreach ($errors->all() as $e)
                      <span> {{$e}} </span><br>
                  @endforeach
              </p>
          @endif
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <input type="number" class="form-control" required="on" name="lectura" placeholder="Ingresar lectura" value="{{old('lectura')}}">
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <?php 
                  $fecha_act = date('yy-m-d');
                  $fecha_min = date("yy-m-d",strtotime($fecha_act."- 15 days")); 
                  
                 ?>

                 @if(Session::has('fecha_lectura'))

                  <input type="date" class="form-control"  required="on" name="fecha_factura" value="{{Session::get('fecha_lectura')}}"  max="<?php echo $fecha_act;?>" min="<?php echo $fecha_min;?>">
                 @else
                  <input type="date" class="form-control"  required="on" name="fecha_factura"   max="<?php echo $fecha_act;?>" min="<?php echo $fecha_min;?>">
              
                 @endif
                  
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" class="form-control" name="estado" value="1">
                    <input type="hidden" class="form-control" name="id_cliente">
                     <input type="hidden" class="form-control" name="id_medidor">
                  </div>
              </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
          <button type="submit  " class="btn btn-primary">GUARDAR</button>
        </div>
      </div>
    </div>
  </div>
</form>
