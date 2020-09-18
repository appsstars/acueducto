
@extends('layouts.admin')
@section('title','Informe mensual')
@section('content')
<style>
	.content{
		max-width: 80%;
		margin: auto;
		display: block;
	}
</style>
<div class="row">
	<div class="container" style="margin-left: 0px">
	<div class="row">
		<div class="col-md-4"></div>
		<div class="col-12 col-md-5">
			<div class="tabla">
				<table class="table table-bordered table-striped" id="tabla-" style=>
					<thead>

						<tr>
							<td>Subsidio nivel 1</td>
							<td>Subsidio nivel 2</td>
							<td>Subsidio nivel 3</td>
						</tr>
					</thead>
					<tbody>
						<?php 
							$cant_metros_t = 0;
							$valor_consumo_t = 0;
							$cargo_fijo_t = 0;
							$subsidio_consumo_t = 0;
							$subsidio_cargo_fijo_temp_t = 0;
							$total_consumo_t = 0;
							$total_cargo_t = 0;
							$otros_cobros_t = 0;
							$total_factura_t = 0;

							//
							$sub_nivel_1 = 0;
							$sub_nivel_2 = 0;
							$sub_nivel_3 = 0;
							$cantidad_registros = count($facturacion_total);
						 ?>
					@foreach($facturacion_total as $d => $c)		

						<?php 
						for ($i=0; $i < count($consumo); $i++) {  ?>
							@if($c->id_factura == $consumo[$i]['numero_lectura'])
							<?php 
								$numero_lectura = $consumo[$i]['numero_lectura']; 
								$codigo_medidor = $consumo[$i]['codigo_medidor'];
								$lectura_anterior = $consumo[$i]['lectura_anterior']; 
								$lectura_actual = $consumo[$i]['lectura_actual']; 
								$fecha_lectura = $consumo[$i]['fecha_lectura']; 
								$precio_metro = $consumo[$i]['precio_metro']; 
								$valor_basico = $consumo[$i]['valor_basico'];
								$valor_consumo = $consumo[$i]['valor_consumo'];
								//	
								$cargo_fijo = $consumo[$i]['cargo_fijo'];
								$otros_cobros = $consumo[$i]['otros_cobros'];
								//
								$consumo_usuario = $consumo[$i]['consumo'];
								$metros_adicionales = $consumo[$i]['metros_adicionales'];
								$precio_normal = $consumo[$i]['precio_normal']; 
								$subsidio_consumo = $consumo[$i]['subsidio_consumo'];  
								$subsidio_cargo_fijo = $consumo[$i]['subsidio_cargo_fijo'];
								$subsidio_cargo_fijo_temp = $consumo[$i]['subsidio_cargo_fijo_temp'];

								$id_nivel = $consumo[$i]['id_nivel'];
								$codigo_usuario = $c->id_cliente;
								$periodo = $c->periodo;
								$ano = $c->ano;
								//
								$cant_metros_t = $cant_metros_t + $consumo_usuario;
								$valor_consumo_t = $valor_consumo_t + $valor_consumo; 
								$cargo_fijo_t = $cargo_fijo_t + $cargo_fijo;
								$otros_cobros_t = $otros_cobros_t + $otros_cobros;

								$res = $subsidio_consumo + $subsidio_cargo_fijo_temp;

								if($id_nivel==1){
									$sub_nivel_1 = $sub_nivel_1 + $res;
								}

								if($id_nivel==2){
									$sub_nivel_2 = $sub_nivel_2 + $res;
								}

								if($id_nivel==3){
									$sub_nivel_3 = $sub_nivel_3 + $res;
								}


							 ?>
							@endif
						<?php } ?>

						
					@endforeach

					<tr >
							<td> <span class="val">  <?php echo number_format($sub_nivel_1,0); ?> </span>  </td>
							<td> <span class="val">  <?php echo number_format($sub_nivel_2,0); ?> </span>  </td>
							<td> <span class="val">  <?php echo number_format($sub_nivel_3,0); ?> </span>  </td>

					</tr>


					</tbody>
					
				</table>
                
			</div>
                </div>
	</div>
</div>
</div>


<script>
	window.onload = function(){
		$('.container').css({'max-width':'95%'});
		$('.container').css({'max-width':'95% !important'});
		$('body table').css({'font-size':'10px','color':'#000'});
		$('table').css({'border':'1px #000 !important'});
		$('.table-striped').css({'background':'#000 !important'});
	}
</script>


@endsection
