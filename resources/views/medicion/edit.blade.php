<form action="{{url('app/medicion')}}" autocomplete="off" method="post" id="edit-lectura">
  {{csrf_field()}}
  {{method_field('put')}}
  <div class="modal fade" id="form-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <input type="hidden" class="form-control"   name="id_factura">
                 <input type="date" class="form-control"  required="on" name="fecha_factura">
                  
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
