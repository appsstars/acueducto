@extends('layouts.admin')
@section('content')
<div class="row">
	<div class="container">
	<div class="row">
		<div class="col-md-2">
			  <a href="{{url('app/pago/create')}}" class="btn btn-outline-info">Volver</a>
		</div>
		<div class="col-md-8"></div>
		<div class="col-md-2">
			{{-- <a href="{{url('app/generar_facturas')}}" class="btn btn-block btn-outline-success btn-sm" style="font-size: 11px">Descargar facturas</a> --}}
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-12 col-md-12">
				<div class="tabla">
					<?php $otros_cobros = 0; ?>
					@if(count($facturacion)>0)
					<table class="table table-bordered table-striped" id="tabla-" style="background-color: whitesmoke;">
						<thead>

							<tr>
								<td>Mes</td>
								<td>Metros de consumo</td>
								<td>Valor consumo</td>
								<td>Cargo fijo</td>
								<td>Subsidio consumo</td>
								<td>Subsidio cargo fijo</td>
								<td>Total</td>
								<td>Opciones</td>
							</tr>
						</thead>
						<tbody>
							<form action="{{route('pago.store')}}" method="post" name="form-pago">
	                       		@foreach($facturacion as $f)
						   			@foreach($creditos as $c)
							   			@if($f->id_medidor==$c->id_medidor)
											<?php $otros_cobros = $c->valor_cuota; ?>
							   			@endif
						   			@endforeach
								<tr>
									<td> {{$f->periodo}} </td>
									<td> {{$f->consumo}} M3</td>
									<td>
										 <?php 
										 	$valor_consumo = $f->consumo * $f->precio_metro; echo number_format($valor_consumo,0);
										  ?> 
									</td>
									<td> {{$f->cargo_fijo}} </td>
									<td> <?php 
											$consumo = $f->consumo;
						                    if($f->consumo>11){
						                        // dd($dato);
						                         $metros_adicionales = $f->consumo - 11;
						                         $precio_normal = $metros_adicionales * $f->precio_metro;
						                         $valor_basico = 11 * $f->precio_metro;
						                         $valor_consumo = $valor_basico + $precio_normal;
						                     }else{
						                          $valor_basico = $consumo * $f->precio_metro;
						                          $valor_consumo = $valor_basico;
						                          $precio_normal = 0;
						                     }
						                    $subsidio_consumo = ($valor_basico * $f->porcentaje) / 100;
						                   	$total_consumo = $valor_consumo - $subsidio_consumo;
						                    //cargos fijos
						                    $subsidio_cargo = ($f->cargo_fijo * $f->porcentaje) / 100;//30/110/0
						                    $subsidio_pagar_cargo = $f->cargo_fijo - $subsidio_cargo;///pagar   $/////00 7.800
						                    echo $subsidio_consumo;
						                    //
										 ?> </td>
										  <td> <?php echo number_format($subsidio_cargo, 0); ?> </td>
										  <td>
									   		<?php 
									   			$total_factura =  $total_consumo +  $subsidio_pagar_cargo;
									   			echo $total_factura;

									   		 ?>
									   </td>
									   <td>
									   		
									   			{{csrf_field()}}
									   			<input type="hidden" name="id_medidor" value="<?php echo $f->id_medidor;?>">
									   			<input type="checkbox" name="id_factura[]" value="<?php echo $total_factura; echo "-".$f->id_factura; ?>">
									   			<input type="hidden" name="otros_cobros" class="form-control" value="0">
									   		
									   </td>

								</tr> 
	                       @endforeach
	                       </form>
	                      
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6" rowspan="2"></td>
								<td>Otros cobros</td>
								<td><button type="button" class="btn btn-outline-success" onclick="pagar()">PAGAR</button></td>
							</tr>
							<tr>
								<td colspan="6" >
									<?php echo number_format($otros_cobros,0); ?>
								</td>
							</tr>
						</tfoot>
					</table>
					 @else
	                     <p class="alert alert-info">Este usuario no tiene facturas pendientes con este medidor.</p>
	                 @endif
				</div>

                </div>
	</div>
</div>
</div>


<!---->
<div class="modal fade" id="form-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-md-4">
        		<div class="form-group">
        			<input type="checkbox" name="confirmacion" onchange="validar()">
        			<span>Agregar cobros</span>
        		</div>
        	</div>
        	<div class="col-md-8">
        		<div class="form-group">
        			<span>Valor</span>
        			<input type="number" name="valor" class="form-control" disabled="true" value="<?php echo $otros_cobros; ?>" min='0' max='<?php echo $otros_cobros; ?>' onchange="validar_cobro()">
        		</div>
        	</div>
        	<div class="col-md-12">
        		<div class="form-group">
        			<button class="btn btn-outline-success btn-submit" onclick="pago_confirma()">PAGAR</button>
        		</div>
        	</div>
        </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!---->


<script src="{{asset('js/controller/FacturaController.js')}}"></script>
@if(Session::has('success'))
    <?php
        echo "<script>
            window.onload = function(){
                toastr.success('".Session::get('success')."');
            }
        </script>";
    ?>
@endif
<script>
	function pagar(){
		 $('#form-create').modal('show');
	}

	function validar(){
		if( $('input[name=confirmacion]').prop('checked') ) {
		    $('input[name=valor]').prop('disabled',false);
		    var val =  $('input[name=valor]').val();
		    $('input[name=otros_cobros]').val(val);
		}else{
			 $('input[name=valor]').prop('disabled',true);
			 $('input[name=otros_cobros]').val(0);
		}
	}

	function validar_cobro(){
		var max =  $('input[name=valor]').attr('max');
		var val =  $('input[name=valor]').val();
		if(val>max){
			var val =  $('input[name=valor]').val(max);
		}
		$('input[name=otros_cobros]').val(val);
	}

	function pago_confirma(){

		$('form[name=form-pago]').submit();
	}
</script>
@endsection
