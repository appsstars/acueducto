
@extends('layouts.admin')
@section('title','Informe mensual')
@section('content')
<style>
	.content{
		max-width: 80%;
		margin: auto;
		display: block;
	}

    .info{
        margin-top: 30px;
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
                	
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{url('img/logo.png')}}" style="display: block;width: 70%; margin-top: 0px; margin-left:40px !important">
                        </div>

                        <div class="col-md-7"></div>
                        <div class="col-md-3">
                            <div class="info">
                                <tr>
                                <td style="text-align: left; margin-rigth:100px !important; font-size: 11px">
                                    <span class="title">ACUEDUCTO VEREDA</span>   </br>
                                    <span class="title">PIJAOS </span> </br>
                                    <span class="title">MUNICIPIO DE </span> </br>
                                    <span class="title">CUCAITA NIT </span> </br>
                                    <span class="title">900087531-8 </span>  </br>
                                </td>
                            </tr>
                            </div>
                        </div>

                    </div>
						<p style='background: #f3f3f3'>

		                	<form action="" method="get">
								<a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
								<input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
								<select name="mes" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
									<option>Mes</option>
									<?php

									    for ($i = 1; $i <= 12 ; $i++) {
									        if(!empty($_REQUEST['mes'])){
									            if($_REQUEST['mes']==$i){
									                echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
									                $i= $i +1;
									            }
									        }
									        if($i<=12){echo '<option value="'.$i.'">'.$i.'</option>';}
									        
									    }
									?>
								</select>
								<select name="anno" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
									<option>Año</option>
									<?php
										$anno_inicio = '2016';
										$anno_actual = date('yy');
										for ($i = $anno_inicio; $i <=$anno_actual ; $i++) {
											if(!empty($_REQUEST['anno'])){
												if($i==$_REQUEST['anno']){
													echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
													 $i= $i +1;
												}
											}
											if($i<=date('yy')){echo '<option value="'.$i.'">'.$i.'</option>';}
												
										}
									 ?>
								</select>

								<select name="tipo" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
									<option value="1" <?php if(!empty($_REQUEST['tipo'])){$op=1; if($op==$_REQUEST['tipo']){echo 'selected="selected"';}} ?>>Todas</option>
									<option value="2" <?php if(!empty($_REQUEST['tipo'])){$op=2; if($op==$_REQUEST['tipo']){echo 'selected="selected"';}} ?>>Pendientes</option>
									<option value="3" <?php if(!empty($_REQUEST['tipo'])){$op=3; if($op==$_REQUEST['tipo']){echo 'selected="selected"';}} ?>>Pagadas</option>
								</select>

			                	<button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>

							<button type='button'  onclick="imprimir()" class="btn btn-outline-warning btn-sm">Imprimir</button>
							</form>
	                	</p>
						
						@if(!empty($_REQUEST['anno']))
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
										$subsidio_cargo_fijo_t = 0;
										$total_consumo_t = 0;
										$total_cargo_t = 0;
										$otros_cobros_t = 0;
										$total_factura_t = 0;
								 ?>
								@foreach($facturacion_total as $f)
									<tr>
										<td> {{ $f->periodo }} </td>
										<td> {{ $f->id_factura }} </td>
										<td> {{ $f->id_cliente }} </td>
										<td> {{ $f->nombre }} </td>
										<td> {{ $f->primer_apellido }} </td>
										<td> {{ $f->segundo_apellido }} </td>
										<td> {{ $f->id_nivel }} </td>
										<td> {{ $f->id_medidor }} </td>
										<td> {{ $f->ano }} </td>
										<td> {{ $f->precio_metro }} </td>
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
									   			@if($f->id_credito==$c->id_credito)
													<?php $otros_cobros = $c->valor_cuota; ?>
													<?php
														echo $otros_cobros;
														$otros_cobros_t = $otros_cobros_t + $otros_cobros;
													 ?>
									   			@endif
									   		@endforeach

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
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> M3 </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Val_consumo </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Cargo fijo</td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Subs_consumo </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Subs_cargo F </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Total_consumo </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Total_cargo</td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'> Otros cobros</td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'>Valor factura</td>
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
                                <tr style="border">
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'  rowspan="2">Total</td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'  colspan="2">Valor servicio</td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'  colspan="2"> Subsidio Municipio  </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important'  colspan="3"> Tarifa usuarios  </td>
                                    <td style='background: #9cb7c3 !important;border:1px white solid !important' >Total ingreso</td>
                                </tr>
                                <tr>
                                    <td colspan="2"> <?php $val_serv = $valor_consumo_t + $cargo_fijo_t; echo number_format($val_serv,0);  ?> </td>
                                    <td colspan="2"> <?php $sub_muni = $subsidio_consumo_t + $subsidio_cargo_fijo_t; echo number_format($sub_muni,0);  ?> </td>
                                    <td colspan="3"> <?php $tarifa_usuarios = $total_consumo_t + $total_cargo_t + $otros_cobros_t; echo number_format($tarifa_usuarios,0);  ?> </td>
                                    <td colspan="2"> <?php $total_ingreso = $sub_muni + $tarifa_usuarios; echo number_format($total_ingreso,0);  ?> </td>
                                </tr>
                            </tbody>
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
        div#cabecera,.navbar,.btn,.title-reporte, select {display: none; }
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



@if(Session::has('success'))
    <?php
        echo "<script>
            window.onload = function(){
                toastr.success('".Session::get('success')."');
            }
        </script>";
    ?>
@endif


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
	}


	function descargar_pdf(){
		print();
	}
</script>




@endsection
