
@extends('layouts.admin')
@section('title','Informe mensual')
@section('content')
<style>
	.content{
		max-width: 80%;
		margin: auto;
		display: block;
	}

@media print {
    @page { margin-bottom: 20px;
      }
}
</style>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card text-center">
                <div class="card-body">
                	@if(!empty($_REQUEST['fecha']))
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{url('img/logo.png')}}" style="display: block;width: 70%; margin-top: 0px; margin-left:40px !important">
                        </div>
                        <div class="col-md-7 ">
                            <h5 style="margin-left:100px">Pagos Realizados :<small>{{$dato['tota_pagos']}}</small></h5>
                        <h6 style="margin-left:100px">fecha : <small id="fecha">{{$fecha}}</small></h6>

                        </div>
                        <div class="col-md-3">
                            <div class="info">
                                
                                <div style="text-align: left; margin-rigth:100px !important; font-size: 11px">
                                    <span class="title">ACUEDUCTO VEREDA</span>   </br>
                                    <span class="title">PIJAOS </span> </br>
                                    <span class="title">MUNICIPIO DE </span> </br>
                                    <span class="title">CUCAITA NIT </span> </br>
                                    <span class="title">900087531-8 </span>  </br>
                                </div>
                           
                            </div>
                        </div>

                    </div>
						<p style='background: #f3f3f3'>

		                	<form  method="get">
								<a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
								<input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
								<input type="date"  name="fecha" value="<?php echo $_REQUEST['fecha']; ?>">
			                	<button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
			                	<button type='button'  onclick="imprimir()" class="btn btn-outline-warning btn-sm">Imprimir</button>
							</form>
	                	</p>

	                    <table class="table table-bordered table-striped" id="tabla-">
							<thead>

								<tr>
									<td style="max-width:60px !important">Periodo</td>
									<td  style="max-width:70px !important">Num_factura</td>
									<td  style="max-width:60px !important">Codigo</td>
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
										$subsidio_cargo_fijo_t = 0;
										$total_consumo_t = 0;
										$total_cargo_t = 0;
										$otros_cobros_t = 0;
										$total_factura_t = 0;
								 ?>
								@foreach($facturacion_total as $f)
									<tr>
										<td> {{$f->periodo}} </td>
										<td> {{$f->id_factura}} </td>
										<td> {{$f->id_cliente}} </td>
										<td> {{$f->nombre}} </td>
										<td> {{$f->primer_apellido}} </td>
										<td> {{$f->segundo_apellido}} </td>
										<td> {{$f->id_nivel}} </td>
										<td> {{$f->id_medidor}} </td>
										<td> {{$f->ano}} </td>
										<td> {{$f->precio_metro}} </td>
										<td>
											{{ $f->consumo }}  <?php $cant_metros_t = $cant_metros_t + $f->consumo; ?>
										 </td>
										<td>
											 <?php
											 	$valor_consumo = $f->consumo * $f->precio_metro; echo number_format($valor_consumo,0);
											 	$valor_consumo_t = $valor_consumo_t + $valor_consumo;
											  ?>
										</td>
										<td>
											{{ $f->cargo_fijo }}
											<?php $cargo_fijo_t = $cargo_fijo_t +  $f->cargo_fijo;?>

										</td>
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

						                    $subsidio_consumo_t = $subsidio_consumo_t + $subsidio_consumo;
						                    $subsidio_cargo_fijo_t = $subsidio_cargo_fijo_t +  $subsidio_cargo;
						                    $total_consumo_t = $total_consumo_t + $total_consumo;
						                    $total_cargo_t = $total_cargo_t + $subsidio_pagar_cargo;

										 ?> </td>
										 <td> <?php echo number_format($subsidio_cargo, 0); ?> </td>
									     <td> <?php
									  		echo number_format($total_consumo,0);
									   ?> </td>
									   <td> <?php
									  		echo number_format($subsidio_pagar_cargo,0);
									   ?> </td>
									   <td>
									   	<?php $otros_cobros = 0; ?>
                                               @foreach($creditos as $c)
                                                   @if($f->id_medidor==$c->medidor)

													<?php 

														$otros_cobros = $c->valor - $c->valor_cuota;
														if($otros_cobros==0){
															//$otros_cobros = $c->valor;
														}else{
															//$otros_cobros = $c->valor - $c->valor_cuota;
														}

													 ?>
													
									   			@endif
									   		@endforeach
											<?php
												$otros_cobros_t = $otros_cobros_t + $otros_cobros;
											 ?>
                                               <?php echo $otros_cobros;  ?>
									   </td>
									   <td>
									   		<?php
									   			$total_factura =  $otros_cobros + $total_consumo +  $subsidio_pagar_cargo;
									   			$total_factura_t = $total_factura_t + $total_factura;
									   			echo $total_factura;

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
									<td>  <?php echo number_format($subsidio_cargo_fijo_t,0); ?> </td>
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
								 	<td colspan="2"> <?php $sub_muni = $subsidio_consumo_t + $subsidio_cargo_fijo_t; echo number_format($sub_muni,0);  ?> </td>
								 	<td colspan="3"> <?php $tarifa_usuarios = $total_consumo_t + $total_cargo_t + $otros_cobros_t; echo number_format($tarifa_usuarios,0);  ?> </td>
								 	<td colspan="2"> <?php $total_ingreso = $sub_muni + $tarifa_usuarios; echo number_format($total_ingreso,0);  ?> </td>
								 </tr>
							</tbody>
							
                	@else
						<div class="alert alert-dismissable" role="alert">
							<strong>Advertencia!</strong> Seleccione un un periodo en para generar un reporte.
						</div>

						<form  method="get">
							<a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
							<input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
                            <input type="date" name="fecha" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px">
		                	<button type="submit"  class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
						</form>



                	@endif

                </div>
                
            </div>
        </div>
    </div>
</div>


@if(!empty($_REQUEST['print']))
    <style>
        div#cabecera,.navbar,.btn,.title-reporte, select, input {display: none; }
    </style>
    <script>
        print();
        setTimeout(function () { $('.btn, .title-reporte').show(); }, 5000);
    </script>
@endif

<script>
	function imprimir(){
     	 var url = window.location;
       location.href = url+'&print=active';

	}

	function buscar(){
		location.reload();
		var url = location.href;
		location.href
	}
</script>


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

</style>

<script>
	window.onload = function(){
		$('.container').css({'max-width':'95%'});
		$('.container').css({'max-width':'95% !important'});
		$('body table').css({'font-size':'9px','color':'#000'});
		$('body table td').css({'padding':'3px'});
		$('table').css({'border':'1px #000 !important'});
		//$('.table-striped').css({'background':'#000 !important'});
	}


	function descargar_pdf(){
		print();
	}
    $("#fecha").datepicker({
    format: 'dd-mm-yy'
    });
</script>


@endsection
