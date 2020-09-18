<form action="{{url('app/reportes')}}" method='get'>
  <div class="modal fade" id="form-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
              <input type="hidden" name='reporte' value="4">
              <input type="hidden" name="id_cliente">
              <select name="mes" class="form-control">
                  <option>Seleccionar</option>
                  <option value="01">Enero</option>
                  <option value="02">Febrero</option>
                  <option value="03">Marzo</option>
                  <option value="04">Abril</option>
                  <option value="05">Mayo</option>
                  <option value="06">Junio</option>
                  <option value="07">Julio</option>
                  <option value="08">Agosto</option>
                  <option value="09">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
              </select>
            </div>
             <div class="col-md-4">
              <select name="ano" class="form-control">
                  <option>Seleccionar</option>
                  <option value="01">2020</option>
              </select>
            </div>
            <div class="col-md-3">
              <button class="btn btn-outline-success btn-block btn-sm">Generar</button>
            </div>
        </div> 
        </div>
      </div>
    </div>
  </div>
</form>
