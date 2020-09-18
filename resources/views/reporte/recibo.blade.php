{{--
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


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-header">

                </div>
                <div class="card-body">

	                    <table class="table table-bordered table-striped" id="tabla-">
							<thead>

								<tr>
									<td style="max-width:60px !important">Periodo</td>
									<td  style="max-width:70px !important">Num_factura</td>
									<td  style="max-width:60px !important">Cod</td>
									<td>Nombre</td>
									<td style="max-width:40px !important">Primer apellido</td>
									<td style="max-width:40px !important">Segundo apellido</td>
									<td  style="max-width:15px !important">Nivel</td>
									<td  style="max-width:50px !important">Cd_medidor</td>
									<td  style="max-width:30px !important">Año</td>
									<td>Valor metro</td>
									<td style="max-width:70px !important">Consumo</td>
									<td style="max-width:30px !important">Val_consumo</td>
									<td  style="max-width:70px !important">Cargo_fijo</td>
									<td>Subs_consu</td>
									<td>Subs_cargo</td>
									<td>Total Consumo</td>
									<td>Total cargo</td>
									<td style="max-width:30px !important">Otros cobros</td>
									<td>Val_factura</td>
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
									 ?>
									@endif
								<?php } ?>

								<tr >
									<td> <span class="val">{{$periodo}}</span>  </td>
									<td> <span class="val">{{$numero_lectura}}</span>  </td>
									<td> <span class="val">{{$codigo_usuario}}</span>  </td>
									<td> <span class="val">{{$c->nombre}}</span>  </td>
									<td> <span class="val">{{$c->primer_apellido}}</span></td>
									<td class="val"><span class="val">{{$c->segundo_apellido}}</span></td>
									<td> <span class="val">{{$id_nivel}}</span></td>
									<td> <span class="val">{{$codigo_medidor}}</span></td>
									<td> <span class="val">{{$ano}}</span></td>
									<td> <span class="val">{{$precio_metro}}</span></td>
									<td> <span class="val">{{$consumo_usuario}}</span></td>
									<td> <span class="val">{{$valor_consumo}}</span></td>
									<td> <span class="val">{{$cargo_fijo}}</span></td>
									<td> <span class="val">
										{{$subsidio_consumo}}
										<?php
												$subsidio_consumo_t =  $subsidio_consumo_t + $subsidio_consumo;

										 ?>


									</span></td>
									<td> <span class="val">

										<?php
											if($id_nivel==11 || $id_nivel==12 ){
												echo $cargo_fijo;

											}else{
												 echo $subsidio_cargo_fijo_temp;

											}

										 ?>

										<?php
											$subsidio_cargo_fijo_temp_t = $subsidio_cargo_fijo_temp_t  + $subsidio_cargo_fijo_temp;

										 ?>

									</span></td>
									<td>
										<?php
											//$valor_factura_basico =  $subsidio_consumo + $precio_normal;
										$valor_factura_basico =  $valor_consumo - $subsidio_consumo;
											if($id_nivel==11 || $id_nivel==12 ){
												$valor_factura_basico =  $valor_consumo + $cargo_fijo;
												echo $valor_factura_basico;
											}else{
												echo $valor_factura_basico;
											}
											$total_consumo_t = $total_consumo_t + $valor_factura_basico;
										?>
									</td>
									<td> <span class="val">
											<?php
												if($id_nivel==11 || $id_nivel==12){
													echo $cargo_fijo;
													$total_cargo_t = $total_cargo_t + $cargo_fijo;

												}else{
													echo $subsidio_cargo_fijo;
													$total_cargo_t = $total_cargo_t + $subsidio_cargo_fijo;
												}


											?>
										</span>
									</td>
									<td>
										<?php echo number_format($otros_cobros,0); ?>
									</td>
									<td>
										<?php
											$total = $valor_factura_basico +  $subsidio_cargo_fijo + $otros_cobros;
											if($id_nivel==11 || $id_nivel==12){
												$total = $valor_factura_basico  + $otros_cobros;
											}
											echo $total;
											$total_factura_t = $total_factura_t  + $total;
										?>
									</td>
								</tr>
								@endforeach

								<tr>
									<td rowspan="4" colspan="10">
										<div class="row">
											<div class="col-md-2">
												<img src="{{url('img/logo.png')}}" style="display: block;width: 100%; margin-top: 20px">
											</div>
											<div class="col-md-3">
												<p style='background: #9cb7c3 !important'>N° de registros - <?php echo  count($facturacion_total); ?></p>
											</div>
										</div>

										<div class="row">
											<div class="col-md-3">
											</div>
											<div class="col-md-8">
													______________________________________________________________ <br>
												<span class="text-center">FIRMA Y SELLO REPRESENTANTE LEGAL</span>

											</div>
										</div>


									</td>
									<td style='background: #9cb7c3 !important'> M3 </td>
									<td style='background: #9cb7c3 !important'> Val_consumo </td>
									<td style='background: #9cb7c3 !important'> Cargo fijo</td>
									<td style='background: #9cb7c3 !important'> Subs_consumo </td>
									<td style='background: #9cb7c3 !important'> Subs_cargo F </td>
									<td style='background: #9cb7c3 !important'> Total_consumo </td>
									<td style='background: #9cb7c3 !important'> Total_cargo</td>
									<td style='background: #9cb7c3 !important'> Otros cobros</td>
									<td style='background: #9cb7c3 !important'>Valor factura</td>
								</tr>
								<tr>
									<td> {{$cant_metros_t}} </td>
									<td>  <?php echo number_format($valor_consumo_t,0); ?> </td>
									<td>  <?php echo number_format($cargo_fijo_t,0); ?> </td>
									<td>  <?php echo number_format($subsidio_consumo_t,0); ?> </td>
									<td>  <?php echo number_format($subsidio_cargo_fijo_temp_t,0); ?> </td>
									<td>  <?php echo number_format($total_consumo_t,0); ?> </td>
									<td>  <?php echo number_format($total_cargo_t,0); ?> </td>
									<td>  <?php echo number_format($otros_cobros_t,0); ?> </td>
									<td>  <?php echo number_format($total_factura_t,0); ?> </td>
								 </tr>
								 <tr>
								 	<td style='background: #9cb7c3 !important'  rowspan="2">Total</td>
								 	<td style='background: #9cb7c3 !important'  colspan="2">Valor servicio</td>
								 	<td style='background: #9cb7c3 !important'  colspan="2"> Subsidio Municipio  </td>
								 	<td style='background: #9cb7c3 !important'  colspan="3"> Tarifa usuarios  </td>
								 	<td style='background: #9cb7c3 !important' >Total ingreso</td>
								 </tr>
								 <tr>
								 	<td colspan="2"> <?php $val_serv = $valor_consumo_t + $cargo_fijo_t; echo number_format($val_serv,0);  ?> </td>
								 	<td colspan="2"> <?php $sub_muni = $subsidio_consumo_t + $subsidio_cargo_fijo_temp_t; echo number_format($sub_muni,0);  ?> </td>
								 	<td colspan="3"> <?php $tarifa_usuarios = $total_consumo_t + $total_cargo_t + $otros_cobros_t; echo number_format($tarifa_usuarios,0);  ?> </td>
								 	<td colspan="2"> <?php $total_ingreso = $sub_muni + $tarifa_usuarios; echo number_format($total_ingreso,0);  ?> </td>
								 </tr>

							</tbody>

						</table>


                </div>
                <div class="card-footer text-muted">
                    2020
                </div>
            </div>
        </div>
    </div>
</div>




<script src="{{asset('js/controller/FacturaController.js')}}"></script>

@include('medicion.form')
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
	function descargar_reporte(){
		$('.container').css({'max-width':'95%'});
		$('.btn-outline-info').hide();
		print();
	}
</script>

<style>
	body .val{
		font-size: 10px !important
	}
	.table-striped{
		background: #000 !important
	}
</style>

<script>
	window.onload = function(){
		$('.container').css({'max-width':'95%'});
		$('.container').css({'max-width':'95% !important'});
		$('body table').css({'font-size':'9px','color':'#000'});
		$('body table td').css({'padding':'3px'});
		$('table').css({'border':'1px #000 !important'});
		$('.table-striped').css({'background':'#000 !important'});
	}


	function descargar_pdf(){
		print();
	}
</script>


@endsection --}}
