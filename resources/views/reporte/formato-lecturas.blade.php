
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
        margin-top: 0px;
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
            	@if(!empty($_REQUEST['mes']))
					<p style='background: #f3f3f3'>

		                	<form action="" method="get">
								<a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
								<input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
								<select name="mes" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
									<?php
									$mes_actual = date('m');


										for ($j = $mes_actual - 2; $j <=$mes_actual ; $j++) {
											if($j==$_REQUEST['mes']){
												echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
											}else{
												echo '<option value="'.$j.'">'.$j.'</option>';
											}
												
										}
									 ?>


								</select>

			                	<button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
			                	<button type='button'  onclick="imprimir()" class="btn btn-outline-warning btn-sm">Imprimir</button>
							</form>
	                	</p>

					 <table class="table table-bordered table-striped" id="tabla-">
							<thead>

								<tr>
									<td >Cd_medidor</td>
									<td >Cod</td>
									<td>Nombre</td>
									<td>Primer apellido</td>
									<td>Segundo apellido</td>
									<td >Nivel</td>
									<?php
										$mes = $_REQUEST['mes'];
										$mes1 = $_REQUEST['mes'];
										$mes2 = $_REQUEST['mes'] + 1;
										$mes3 = $_REQUEST['mes'] + 2;
										$mes4 = $_REQUEST['mes'] + 3;
										
										if($mes1>=13){ $mes1 = $mes1 - 12;}
										if($mes2>=13){ $mes2 = $mes2 - 12;}
										if($mes3>=13){ $mes3 = $mes3 - 12;}
										if($mes4>=13){ $mes4 = $mes4 - 12;}
									?>
                                    <td ><span> <?php echo $mes1; ?> </span></td>
									<td ><span> <?php echo $mes2; ?> </span></td>
									<td ><span> <?php echo $mes3; ?> </span></td>
									<td ><span> <?php echo $mes4; ?> </span></td>
								</tr>
							</thead>
							<tbody>
							<script>
								var data = @json($formato);
							</script>
							@foreach($formato as  $f)
							    <tr>
									<td> {{$f['codigo_medidor']}} </td>
									<td> {{$f['codigo_usuario']}} </td>
									<td> {{$f['nombre']}} </td>
									<td> {{$f['primer_apellido']}} </td>
									<td> {{$f['segundo_apellido']}} </td>
									<td> {{$f['nivel']}} </td>
									<td> {{$f['mes1']}} </td>
									<td> {{$f['mes2']}} </td>
									<td> {{$f['mes3']}} </td>
									<td> {{$f['mes4']}} </td>
									
								</tr>
							@endforeach
							</tbody>

						</table>

            	@else
					<div class="alert alert-dismissable" role="alert">
							<strong>Advertencia!</strong> Seleccione un un periodo en para generar un reporte.
						</div>

						<form action="" method="get">
							<a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
							<input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
							<select name="mes" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
								<option>Mes</option>
								<?php
								$mes_actual = date('m');
								$j = $mes_actual - 1;
									for ($j; $j <=$mes_actual ; $j++) {
										echo '<option value="'.$j.'">'.$j.'</option>';
									}
								 ?>
							</select>

		                	<button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
						</form>

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
		$('body table td').css({'padding':'10px'});
		//$('table').css({'border':'1px #000 !important'});
		//$('.table-striped').css({'background':'#000 !important'});
	}


	function descargar_pdf(){
		print();
	}
</script>


@endsection
