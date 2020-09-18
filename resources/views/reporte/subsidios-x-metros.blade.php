
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
                    Reportes
                </div>
                <div class="card-body">
                	@if(!empty($_REQUEST['anno']))

						<p style='background: #f3f3f3'>
	                		
		                	<form action="" method="get">
								<a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
								<input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
								<select name="mes" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
									<option>Mes</option>
									<?php 
										for ($j = 1; $j <=12 ; $j++) {
											if($j==$_REQUEST['mes']){
												echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
											}else{
												echo '<option value="'.$j.'">'.$j.'</option>';
											}
										}
									 ?>
								</select>
								<select name="anno" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
									<option>Año</option>
									<?php 
										$anno_inicio = '2016';
										$anno_actual = date('yy');
										for ($i = $anno_inicio; $i <=$anno_actual ; $i++) { 
											if($i==$_REQUEST['anno']){
												echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
											}
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
									 ?>
								</select>
			                	<button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
			                	<button type='button'  onclick="imprimir()" class="btn btn-outline-warning btn-sm">Imprimir</button>
							</form>
	                	</p>

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
								$sub_nivel_4 = 0;
								$sub_nivel_5 = 0;
								$cantidad_registros = count($facturacion_total);
							 ?>
		                	
	                    <table class="table table-bordered table-striped" id="tabla-">
							<thead>

								<tr>
									<td> Nivel 1</td>
									<td> Nivel 2</td>
									<td> Nivel 3</td>
									<td> Nivel 4</td>
									<td> Nivel 5</td>
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

								<?php 
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
				                    
				                    //
				                    
				                    $subsidio_consumo_t = $subsidio_consumo_t + $subsidio_consumo;
				                    $subsidio_cargo_fijo_t = $subsidio_cargo_fijo_t +  $subsidio_cargo;
				                    $total_consumo_t = $total_consumo_t + $total_consumo;
				                    $total_cargo_t = $total_cargo_t + $subsidio_pagar_cargo;
				                    
								 ?>

								 <?php 
								 $id_nivel = $f->id_nivel;
										$res = $subsidio_consumo + $subsidio_cargo;

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

								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td> <span class="val">  <?php echo number_format($sub_nivel_1,0); ?> </span>  </td>
									<td> <span class="val">  <?php echo number_format($sub_nivel_2,0); ?> </span>  </td>
									<td> <span class="val">  <?php echo number_format($sub_nivel_3,0); ?> </span>  </td>
									<td> <span class="val">  <?php echo number_format($sub_nivel_4,0); ?> </span>  </td>
									<td> <span class="val">  <?php echo number_format($sub_nivel_5,0); ?> </span>  </td>
								</tr>
							</tfoot>
							
						</table>						

                	@endif
	                	 
                </div>
                <div class="card-footer text-muted">
                    2020
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
</script>


@endsection
