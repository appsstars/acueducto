
@extends('layouts.admin')
@section('content')
<div class="row">
	<div class="container">
		<div class="justify-content-center">
			<form action="">
				<div class="row">
					<div class="col-md-6">
						  <a href="{{url('app')}}" class="btn btn-outline-info">Volver</a>
					</div>
					<div class="col-sm-5 col-md-4 col-lg-4" >
			            
			        </div>
			        <div class="col-sm-5 col-md-2 col-lg-2">
			            
			        </div>
				</div>
			</form>
            <div class="card text-center">
                <div class="justify-content-center">
					<div class="tabla">
							<table class="table table-bordered table-striped" id="myTable">
								<thead>

									<tr>
										<td>Nombres</td>
										<td>N° Documento</td>
			                            <td>Medidor</td>
			                            <td>Consumo</td>
			                            <td>Lectura anterior</td>
										<td>Ultima lectura</td>
										<td>Fecha factura</td>
										<td>Fecha limite</td>
										<td>Valor factura</td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</thead>
								<tbody>
			                        @foreach ($lecturas as $l)
			                        	<tr>
			                        		<td> {{$l['nombre']}} {{$l['primer_apellido']}} </td>
			                        		<td> {{$l['documento']}}  </td>
			                        		<td> {{$l['id_medidor']}}  </td>
			                        		<td> {{$l['consumo']}}  </td>
			                        		<td> {{$l['lectura_anterior']}}  </td>
			                        		<td> {{$l['lectura']}}  </td>
			                        		<td> {{$l['fecha_factura']}}  </td>
			                        		<td> {{$l['fecha_limite']}}  </td>
			                        		<td> <?php echo number_format($l['total_temporal'],0); ?>  </td>
			                        		<td> <button type='button' onclick="eliminar_lectura('<?php echo $l['id_medidor']; ?>')" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button></td>

			                        		<td> <button type='button' onclick="editar_lectura('<?php echo $l['id_medidor']; ?>','<?php echo $l['lectura']; ?>','<?php echo $l['fecha_factura']; ?>','<?php echo $l['id_factura']; ?>')" class="btn btn-sm btn-outline-info"><i class="fa fa-edit"></i></button></td>
			                        		<td> <button type='button' onclick="getCliente('<?php echo $l['id_cliente']; ?>','<?php echo $l['id_medidor']; ?>')" class="btn btn-sm btn-outline-success"><i class="fa fa-plus"></i></button></td>

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

<div class="modal fade" id="lecturas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLongTitle">Eliminar lectura</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
     	<table class="table table-bordered">
     		<thead>
     			<tr>
     				<td>Lectura</td>
     				<td>Registro creado</td>
     				<td>Estado</td>
     				<td></td>
     			</tr>
     		</thead>
     		<tbody></tbody>
     	</table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
    </div>
  </div>
</div>
</div>



<script src="{{asset('js/controller/FacturaController.js')}}"></script>
@include('medicion.form')
@include('medicion.edit')

@if(Session::has('success'))
    <?php
        echo "<script>
            window.onload = function(){
                toastr.success('".Session::get('success')."');
            }
        </script>";
    ?>
@endif

@if(Session::has('alert'))
    <?php
        echo "<script>
            window.onload = function(){
                toastr.warning('".Session::get('alert')."');
            }
        </script>";
    ?>
@endif

<script>
	function editar_lectura(id_medidor, lectura, fecha, id_factura){
		$('#form-edit').modal('show');
		$('#form-edit input[name=lectura]').val(lectura);
		var url = "{{url('app/medicion')}}";
		$('#edit-lectura').attr('action',url+'/'+id_medidor);
		$('#form-edit input[name=fecha_factura]').val(fecha);
		$('#form-edit input[name=id_factura]').val(id_factura);
	}

	function eliminar_lectura(id_medidor){
		var url = "{{url('app/lectura/eliminar')}}/"+id_medidor;
		var r = confirm('¿Esta seguro de eliminar la lectura?');
		if(r){
			$.ajax({
				url:url,
				method:'get',
				success:function(r){
					var lecturas = '';
					$.each(r, function(e){
						var status = '';
						if(this.estado==1){
							status = 'Sin pagar';
						}else{
							status = 'Pagada';
						}
						lecturas += ` 
							<tr>	
								<td>${this.lectura}</td>
								<td>${this.created_at}</td>
								<td>${status}</td>
								<td><button type="button" class="btn btn-outline-danger" onclick="confirm_delete(${this.id_lectura})">ELiminar</button></td>
							</tr>
								
						 `;
					});
					$('#lecturas').modal('show');
					$('#lecturas tbody').html(lecturas);
				}
			})
		}
	}

	function confirm_delete(id_lectura){
		var url = "{{url('app/lectura/eliminar/confirm')}}/"+id_lectura;
		
			$.ajax({
				url:url,
				method:'get',
				success:function(r){
					 toastr.warning('ELiminado con exito');
					 $('#lecturas').modal('hide');
				}
			})
	}
</script>


<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        var table = $('#myTable').DataTable({
        	"lengthMenu": [[300], ["todos"]]
        });

        table.order( [ 2, 'asc' ] ).draw();
    });
</script>
@endsection



