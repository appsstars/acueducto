
@extends('layouts.admin')
@section('title','Facturas')
@section('content')
<script>
    data = [];
</script>
<style>
	.content{
		max-width: 80%;
		margin: auto;
		display: block;
	}
    .graficas{
    background-color: aqua;
    width: 200px;
    height: 200px;
    border: 10px black;
    }
    .reporte{
        border-bottom: gray 1px solid;
        height: 70px;
        margin-bottom: 20px;
        margin-top: 20px;
    }
    a .boton{
        color: black;
    }
    .anno{
        border-bottom: gray 1px solid;
        margin-top: 5px;

    }
    .space{
        padding-top: 5px;
    }
</style>


@if(!empty($_REQUEST['mes']))
    <div class="container">
        <div class="rowr">
            <div class="col-md-12">
                <div class="card text-center">
                    <div class="card-body">
                        <p style='background: #f3f3f3'>
                            <form action="" method="get">
                                <a href="{{url('app/reportes?reporte=4')}}" class="text-left btn btn-outline-danger">Regresar</a>
                                <input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">

                                <select name="mes" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
                                    <option>Mes</option>
                                    <?php

                                        for ($i = 1; $i <= 12 ; $i++) {

                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                    ?>
                                </select>
                                <select name="anno" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
                                    <option>Año</option>
                                    <?php
                                        $anno_inicio = '2016';
                                        $anno_actual = date('yy');
                                        for ($i = $anno_inicio; $i <=$anno_actual ; $i++) {
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                    ?>
                                </select>
                                <input type="text" name="id_medidor" placeholder="Codigo del Medidor">
                                <button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
                                <button type='button'  onclick="imprimir()" class="btn btn-outline-warning btn-sm">Imprimir</button>
                            </form>
                        </p>
                        <div class="container">
                            <div class="row align-items-center justify-content-center" >
                                <?php $j = 1;
                            set_time_limit(0);
                            ini_set("memory_limit",-1);
                            ini_set('max_execution_time', 0);
                            ?>

                            <div class="col-md-6 row align-items-center justify-content-center title-reporte reporte">
                                <p class=" text-center" style="margin-top:10px"><h4>Reportes Facturas</h4></p>
                            </div>

                            {{-- <div class="col-md-12 row align-items-center justify-content-center">
                                <a href="{{ url('app/medicion')}}" class="btn btn-outline-info btn-sm">Volver</a>
                                <span class="col-md-4 border-left"></span>
                                <button href="" onclick="imprimir()" class="btn btn-outline-warning btn-sm">Imprimir</button>
                            </div> --}}
                            <script>
                                var graficas = @json($graficas);
                            </script>

                            <?php $cont = 0; ?>

                            @foreach($consumo as $d => $c)

                                <?php

                                    $id_facturacion = $c['id_facturacion'];
                                    $numero_lectura = $c['id_factura'];
                                    $codigo_medidor = $c['codigo_medidor'];
                                    $zona = $c['zona'];
                                    $lectura_anterior = $c['lectura_anterior'];
                                    $lectura_actual = $c['lectura_actual'];
                                    $fecha_lectura = $c['fecha_lectura'];
                                    $fecha_limite = $c['fecha_limite'];  //la factura individual
                                    $fecha_envio = $c['fecha_facturacion'];

                                    $precio_metro = $c['precio_metro'];
                                    $valor_basico = $c['valor_basico'];
                                    $valor_consumo = $c['valor_consumo'];
                                    //
                                    $cargo_fijo = $c['cargo_fijo'];
                                    $otros_cobros = $c['otros_cobros'];
                                    //
                                    $consumo_usuario = $c['consumo'];
                                    $metros_adicionales = $c['metros_adicionales'];
                                    $precio_normal = $c['precio_normal'];
                                    $subsidio_consumo = $c['subsidio_consumo'];
                                    //$subsidio_cargo_fijo = $c['subsidio_cargo_fijo'];
                                    $subsidio_cargo_fijo = $c['subsidio_cargo'];

                                    $total_temporal = $c['total_temporal'];

                                    $id_nivel = $c['id_nivel'];
                                    $codigo_usuario = $c['id_cliente'];
                                    $periodo = $c['periodo'];
                                    $ano = $c['ano'];
                                ?>

                                <div class="col-xs-12 col-md-7" style="height: 1511px !important">
                                    <div class="recibo">
                                        <div  class="informacion-usuario-img" style="border: 1px #777 solid;" >
                                            <table class="table1">
                                                <thead >
                                
                                                    <tr>
                                                        <td>
                                                            <img src="{{url('img/logo.png')}}" class="logo-recibo" style="display: block;max-width: 80px;">
                                                        </td>
                                                        <td style="text-align: left !important; font-size: 12px; color:black">
                                                            <span class="title">ACUEDUCTO VEREDA</span>   </br>
                                                            <span class="title">PIJAOS </span> </br>
                                                            <span class="title">MUNICIPIO DE </span> </br>
                                                            <span class="title">CUCAITA NIT </span> </br>
                                                            <span class="title">900087531-8 </span>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                {!! DNS1D::getBarcodeHTML($numero_lectura, "C128")!!}
                                                               
                                                            </div>
                                                            <div style="text-align: center; color:black">
                                                                 <span style="text-align: center;"> <?php echo $numero_lectura; ?> </span>
                                                            </div>
                                                            <div style="text-align: center ;position: absolute; padding: 0 14px; color:black">
                                                                    <span><strong class="frase">{{Auth::user()->frase}}</strong></span> 
                                                            </div>

                                                            
                                                        </td>
                                                        <td class="text-center;" style="padding-left: 10px; width: 24%; padding-bottom:15px; color:black">
                                                            <span >Vigilado por SSPD</span>
                                                        </td>

                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <span style="font-weight: bold;">Información del usuario</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:10%; '>Documento</td>
                                                        <td style='width:30%'>Nombre</td>
                                                        <td style='width:30%'>Primer apellido</td>
                                                        <td style='width:30%'>Segundo apellido</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> <span class="val"  style="font-size: 10px">{{$c['documento']}}</span>  </td>
                                                        <td> <span class="val" style="font-size: 10px">{{$c['nombre']}}</span>  </td>
                                                        <td> <span class="val"  style="font-size: 10px">{{$c['primer_apellido']}}</span></td>
                                                        <td class="val"  style="font-size: 10px"><span class="val">{{$c['segundo_apellido']}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0;" >
                                            <span style="font-weight: bold;">Información del tecnica</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:10%;'>Cod_lectura </td>
                                                        <td style='width:10%;'>Cod_medidor</td>
                                                        <td style='width:30%;'>Zona</td>
                                                        <td style='width:10%;'>Nivel
                                                        <td style='width:10%;'>Periodo</td>
                                                        <td style='width:10%;'>Año</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align: center;"> <span class="val"> {{$numero_lectura}} </span>  </td>
                                                        <td> <span class="val">{{$codigo_medidor}}</span>  </td>
                                                        <td> <span class="val" style="font-size: 11px">{{$zona}}</span>  </td>
                                                        <td> <span class="val">{{$id_nivel}}</span></td>
                                                        <td class="val"><span class="val">{{$periodo}}</span></td>
                                                        <td class="val"><span class="val">{{$ano}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <span style="font-weight: bold;">Determinación de consumo</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:20%;'>Lectura anterior</td>
                                                        <td style='width:20%;'>Lectura actual</td>
                                                        <td style='width:20%;'>Consumo M3</td>
                                                        <td style='width:20%;'>Fecha lectura </td>
                                                        <td style='width:20%;'>Valor metro </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> {{$lectura_anterior}} </td>
                                                        <td>  {{$lectura_actual}} </td>
                                                        <td> {{$consumo_usuario}} </td>
                                                        <td> {{$fecha_lectura}} </td>
                                                        <td> {{$precio_metro}} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <span style="font-weight: bold;">Liquidación consumo periodo</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:20%;'>Consumo basico</td>
                                                        <td style='width:20%;'>con_comp</td>
                                                        <td style='width:20%;'>con_suntua</td>
                                                        <td style='width:20%;'>Val_consumo</td>
                                                        <td style='width:20%;'>Cargo_fijo</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> {{$consumo_usuario}} </td>
                                                        <td> {{$metros_adicionales}} </td>
                                                        <td> 0 </td>
                                                        <td> {{$valor_consumo}} </td>
                                                        <td> {{$cargo_fijo}} </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td>Valor basico</td>
                                                        <td>con_comp</td>
                                                        <td>con_suntua</td>
                                                        <td>Subsidio consumo</td>
                                                        <td>Subsidio Cargo fijo</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> {{$valor_basico}} </td>
                                                        <td> {{$precio_normal}} </td>
                                                        <td> 0 </td>
                                                        <td> {{$subsidio_consumo}}  </td>
                                                        <td> {{$subsidio_cargo_fijo}}  </td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >

                                            <table style="margin-left: 10px">
                                                </tbody>
                                                    <tr>
                                                        <td style="width: 150px; padding: 0" rowspan="111">
                                                            <canvas id="id{{$id_facturacion}}" width="160px" height="130%"></canvas>
                                                            <script type="text/javascript">

                                                                var valores_consumo = graficas.filter(number => number.id_facturacion == <?php echo $id_facturacion;?> );
                                                                var mes1 = valores_consumo[0]['consumo'];
                                                                var mes2 = valores_consumo[1]['consumo'];
                                                                var mes3 = valores_consumo[2]['consumo'];
                                                                var mes4 = valores_consumo[3]['consumo'];
                                                                var mes5 = valores_consumo[4]['consumo'];
                                                                var mes6 = valores_consumo[5]['consumo'];
                                                                console.log(mes1);

                                                                console.log(valores_consumo);
                                                                var id = 'id'+{{$id_facturacion}} ;
                                                                var ctx = document.getElementById(id).getContext('2d');
                                                                data[id] =  new Chart(ctx, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: [
                                                                        valores_consumo[0]['periodo']+'/'+valores_consumo[0]['anno'],
                                                                        valores_consumo[1]['periodo']+'/'+valores_consumo[1]['anno'],
                                                                        valores_consumo[2]['periodo']+'/'+valores_consumo[2]['anno'],
                                                                        valores_consumo[3]['periodo']+'/'+valores_consumo[3]['anno'],
                                                                        valores_consumo[4]['periodo']+'/'+valores_consumo[4]['anno'],
                                                                        valores_consumo[5]['periodo']+'/'+valores_consumo[5]['anno']
                                                                    ],
                                                                    datasets: [{
                                                                        label: 'Consumo mes',
                                                                        data: [mes1, mes2, mes3, mes4, mes5, mes6],
                                                                        backgroundColor: [
                                                                            'rgba(255, 99, 132, 0.2)',
                                                                            'rgba(54, 162, 235, 0.2)',
                                                                            'rgba(255, 206, 86, 0.2)',
                                                                            'rgba(75, 192, 192, 0.2)',
                                                                            'rgba(153, 102, 255, 0.2)',
                                                                            'rgba(255, 159, 64, 0.2)'
                                                                        ],
                                                                        borderColor: [
                                                                            'rgba(255, 99, 132, 1)',
                                                                            'rgba(54, 162, 235, 1)',
                                                                            'rgba(255, 206, 86, 1)',
                                                                            'rgba(75, 192, 192, 1)',
                                                                            'rgba(153, 102, 255, 1)',
                                                                            'rgba(255, 159, 64, 1)'
                                                                        ],
                                                                        borderWidth: 1
                                                                    }]
                                                                },
                                                                options: {
                                                                    scales: {
                                                                        yAxes: [{
                                                                            ticks: {
                                                                                beginAtZero: true
                                                                            }
                                                                        }]
                                                                    }
                                                                }
                                                            });
                                                        </script>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table style="margin-left: 10px">
                                                <tbody>
                                                    <tr>
                                                    <td style="font-weight: bold; !important">Total_Consumo</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:5px; padding-top:10px !important"> <?php
                                                                    //$valor_factura_basico =  $subsidio_consumo + $precio_normal;
                                                            $valor_factura_basico =  $valor_consumo - $subsidio_consumo;
                                                                    if($id_nivel==11 || $id_nivel==12 ){
                                                                        $valor_factura_basico =  $valor_consumo;
                                                                        echo number_format($valor_factura_basico,0);
                                                                    }else{
                                                                        echo number_format($valor_factura_basico,0);
                                                                    }
                                                                ?>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold; !important">Total_Cargo</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:10px; padding-top:10px !important">
                                                            <?php
                                                            if($id_nivel==11 || $id_nivel==12){
                                                                echo number_format($cargo_fijo,0);
                                                                $total_cargo = $cargo_fijo;

                                                            }else{
                                                            $total_cargo = $cargo_fijo - $subsidio_cargo_fijo;
                                                                echo number_format($total_cargo,0);
                                                            }

                                                                ?>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                            <table style="margin-left: 10px">
                                                <span class="border-left"></span>
                                                <thead>
                                                    <tr>
                                                        <td style="font-weight: bold; !important">Fecha_envio</td><br>

                                                    </tr>
                                                    <tr>
                                                        <td> {{$fecha_envio}} </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                    <td style="font-weight: bold; !important">Fecha_pago</td><br>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$fecha_limite}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold; !important">Total_Factura</td>
                                                        <td style="padding-left:5px; padding-top:10px !important">
                                                            <b>

                                                                <?php
                                                                    $total_factura = $c['valor_factura_actual'];
                                                                    echo number_format($total_temporal);

                                                                ?>

                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold;  !important">Otros_Cobros:</td>
                                                        <td style="padding-left:20px; color:red; padding-top:10px !important">
                                                            <b>

                                                                <?php
                                                                    echo number_format($otros_cobros,0);
                                                                ?>

                                                            </b>


                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <span style="font-weight: bold;">Saldos anteriores</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style="font-weight: bold;  !important">Factura anterior:</td>
                                                        <td style="padding-left:5px; color:red; padding-left:20px; !important"> <?php echo number_format($c['saldo_anterior'], 0); ?> </td>
                                                        <td style="font-weight: bold; padding-left:120px; !important">TOTAL A PAGAR:</td>
                                                        <td style="padding-left:30px; !important"> <b><?php $total_pagar = $total_temporal + $c['saldo_anterior'] + $otros_cobros; echo number_format($total_pagar,0); ?></b></td>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>

                                        <div class="corte">
                                        </div>

                                        <div class="informacion-usuario-img" style="border: 1px #777 solid;margin: 4px 0" >
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td>
                                                            <img src="{{url('img/logo.png')}}" style="display: block;max-width: 80px;">
                                                        </td>
                                                        <td style="color: black; text-align:left">
                                                            <span class="title">ACUEDUCTO VEREDA</span>  </br>
                                                            <span class="title">PIJAOS </span> </br>
                                                            <span class="title">MUNICIPIO DE </span> </br>
                                                            <span class="title">CUCAITA NIT </span> </br>
                                                            <span class="title">900087531-8 </span>  </br>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                {!! DNS1D::getBarcodeHTML($numero_lectura, "C128")!!}
                                                            </div>
                                                            <div style="text-align: center;">
                                                            <span style="text-align: center;"> <?php echo $numero_lectura; ?> </span> <br>
                                                            </div>
                                                        </td>
                                                        <td  style="padding-left: 10px; width: 24%; padding-bottom:45px;color:black">
                                                            <span style="text-align: center;">Vigilado por SSPD</span>
                                                        </td>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0;" >
                                            <span style="font-weight: bold;">Información del tecnica</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:10%;'>Cod_lectura </td>
                                                        <td style='width:10%;'>Cod_medidor</td>
                                                        <td style='width:30%;'>Zona</td>
                                                        <td style='width:10%;'>Nivel
                                                        <td style='width:10%;'>Periodo</td>
                                                        <td style='width:10%;'>Año</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align: center;"> <span class="val"> {{$numero_lectura}} </span>  </td>
                                                        <td> <span class="val">{{$codigo_medidor}}</span>  </td>
                                                        <td> <span class="val">{{$zona}}</span>  </td>
                                                        <td> <span class="val">{{$id_nivel}}</span></td>
                                                        <td class="val"><span class="val">{{$periodo}}</span></td>
                                                        <td class="val"><span class="val">{{$ano}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <span style="font-weight: bold;">Información del usuario</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:10%; '>Documento</td>
                                                        <td style='width:30%'>Nombre</td>
                                                        <td style='width:30%'>Primer apellido</td>
                                                        <td style='width:30%'>Segundo apellido</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> <span class="val"  style="font-size: 10px">{{$c['documento']}}</span>  </td>
                                                        <td> <span class="val" style="font-size: 10px">{{$c['nombre']}}</span>  </td>
                                                        <td> <span class="val"  style="font-size: 10px">{{$c['primer_apellido']}}</span></td>
                                                        <td class="val"  style="font-size: 10px"><span class="val">{{$c['segundo_apellido']}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <span style="font-weight: bold;">Determinación de consumo</span>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <td style='width:20%;'>Lectura anterior</td>
                                                        <td style='width:20%;'>Lectura actual</td>
                                                        <td style='width:20%;'>Consumo M3</td>
                                                        <td style='width:20%;'>Fecha lectura </td>
                                                        <td style='width:20%;'>Valor metro </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> {{$lectura_anterior}} </td>
                                                        <td>  {{$lectura_actual}} </td>
                                                        <td> {{$consumo_usuario}} </td>
                                                        <td> {{$fecha_lectura}} </td>
                                                        <td> {{$precio_metro}} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                            <table style="margin-left: 10px">
                                                </tbody>
                                                    <tr>
                                                        <td style="width: 150px; padding: 0" rowspan="111">
                                                            <canvas id="{{$id_facturacion}}" width="160px" height="130%"></canvas>
                                                            <script type="text/javascript">

                                                                var valores_consumo = graficas.filter(number => number.id_facturacion == <?php echo $id_facturacion;?> );
                                                                var mes1 = valores_consumo[0]['consumo'];
                                                                var mes2 = valores_consumo[1]['consumo'];
                                                                var mes3 = valores_consumo[2]['consumo'];
                                                                var mes4 = valores_consumo[3]['consumo'];
                                                                var mes5 = valores_consumo[4]['consumo'];
                                                                var mes6 = valores_consumo[5]['consumo'];
                                                                console.log(mes1);

                                                                console.log(valores_consumo);
                                                                var id = {{$id_facturacion}} ;
                                                                var ctx = document.getElementById(id).getContext('2d');
                                                                data[id] =  new Chart(ctx, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: [
                                                                        valores_consumo[0]['periodo']+'/'+valores_consumo[0]['anno'],
                                                                        valores_consumo[1]['periodo']+'/'+valores_consumo[1]['anno'],
                                                                        valores_consumo[2]['periodo']+'/'+valores_consumo[2]['anno'],
                                                                        valores_consumo[3]['periodo']+'/'+valores_consumo[3]['anno'],
                                                                        valores_consumo[4]['periodo']+'/'+valores_consumo[4]['anno'],
                                                                        valores_consumo[5]['periodo']+'/'+valores_consumo[5]['anno']
                                                                    ],
                                                                    datasets: [{
                                                                        label: 'Consumo mes',
                                                                        data: [mes1, mes2, mes3, mes4, mes5, mes6],
                                                                        backgroundColor: [
                                                                            'rgba(255, 99, 132, 0.2)',
                                                                            'rgba(54, 162, 235, 0.2)',
                                                                            'rgba(255, 206, 86, 0.2)',
                                                                            'rgba(75, 192, 192, 0.2)',
                                                                            'rgba(153, 102, 255, 0.2)',
                                                                            'rgba(255, 159, 64, 0.2)'
                                                                        ],
                                                                        borderColor: [
                                                                            'rgba(255, 99, 132, 1)',
                                                                            'rgba(54, 162, 235, 1)',
                                                                            'rgba(255, 206, 86, 1)',
                                                                            'rgba(75, 192, 192, 1)',
                                                                            'rgba(153, 102, 255, 1)',
                                                                            'rgba(255, 159, 64, 1)'
                                                                        ],
                                                                        borderWidth: 1
                                                                    }]
                                                                },
                                                                options: {
                                                                    scales: {
                                                                        yAxes: [{
                                                                            ticks: {
                                                                                beginAtZero: true
                                                                            }
                                                                        }]
                                                                    }
                                                                }
                                                            });
                                                        </script>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table style="margin-left: 10px">
                                                <tbody>
                                                    <tr>
                                                    <td style="font-weight: bold; !important">Total_Consumo</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:5px; padding-top:10px !important"> <?php
                                                                    //$valor_factura_basico =  $subsidio_consumo + $precio_normal;
                                                            $valor_factura_basico =  $valor_consumo - $subsidio_consumo;
                                                                    if($id_nivel==11 || $id_nivel==12 ){
                                                                        $valor_factura_basico =  $valor_consumo;
                                                                        echo number_format($valor_factura_basico,0);
                                                                    }else{
                                                                        echo number_format($valor_factura_basico,0);
                                                                    }
                                                                ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-weight: bold; !important">Total_Cargo</td>

                                                    </tr>
                                                    <tr>
                                                        <td style="padding-left:10px; padding-top:10px !important">
                                                            <?php
                                                            if($id_nivel==11 || $id_nivel==12){
                                                                echo number_format($cargo_fijo,0);
                                                                 $total_cargo = $cargo_fijo;

                                                            }else{
                                                                  $total_cargo = $cargo_fijo - $subsidio_cargo_fijo;
                                                                echo number_format($total_cargo,0);
                                                            }

                                                                ?>
                                                            </td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                            <table style="margin-left: 10px">
                                                <span class="border-left"></span>
                                                <thead>
                                                    <tr>
                                                        <td style="font-weight: bold; !important">Saldos_Anteriores</td><br>
                                                        <td style="padding-left:5px; color:red; padding-top:15px !important"><b><?php echo number_format($c['saldo_anterior'], 0); ?> </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="font-weight: bold;">Total_Factura:</td>
                                                        <td style="padding-left:5px; padding-top:15px !important">
                                                            <b>
                                                                <?php
                                                                    $total_factura = $c['valor_factura_actual'];
                                                                    echo number_format($total_temporal);

                                                                ?>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-top:25px font-weight: bold;">Otros_Cobros:</td>
                                                        <td style="padding-left:5px; color:red; padding-top:10px !important">
                                                            <b>
                                                                <?php

                                                                    echo number_format($otros_cobros,0);

                                                                ?>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total_Pagar:</td>
                                                        <td style="padding-left:5px; padding-top:10px !important">
                                                            <b><?php $total_pagar = $total_factura + $c['saldo_anterior'] + $otros_cobros; echo number_format($total_pagar,0); ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                                <div class="informacion-usuario" style="border: 1px #777 solid;margin: 4px 0" >
                                                    <span style="font-weight: bold;">Información adicional</span>
                                                    <table  class="footer-recibo">
                                                        <thead>
                                                            <tr>
                                                                <td style="width: 20%">Fecha_envio</td>
                                                                <td style="width: 20%">Fecha_pago</td>
                                                                <td style="width: 20%">Celular</td>
                                                                <td style="width: 20%">Correo</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td> {{$fecha_envio}} </td>
                                                                <td>  {{$fecha_limite}} </td>
                                                                <td><span>{{Auth::user()->telefono}}</span></td>
                                                                <td>{{Auth::user()->email}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>


                                                </div>

                                                <?php $j= $j +1; ?>
                                                <?php
                                                    if ($d%2==0){
                                                    }else{

                                                    }


                                                ?>
                                            </div>

                                            <div class="space"></div>
                                            <div class="col-md-12 row align-items-center justify-content-center">
                                                <p class="anno text-center">{{$ano}}</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                              
                            @endforeach

                        </div>
                        </div>
@else
                        <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card text-center">
                                            <div class="alert alert-dismissable" role="alert">
                                                    <strong>Advertencia!</strong> Seleccione un un periodo en para generar un reporte.
                                            </div>

                                            <form action="" method="get">
                                                <a href="{{url('app/reportes')}}" class="text-left btn btn-outline-danger">Regresar</a>
                                                <input type="hidden" name="reporte" value="<?php echo $_REQUEST['reporte'];?>">
                                                <select name="mes" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
                                                    <option>Mes</option>
                                                    <?php

                                                        for ($i = 1; $i <= 12 ; $i++) {

                                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                                        }
                                                        ?>
                                                </select>
                                                <select name="anno" style="border: 1px blue solid;padding: 6px 10px; margin-top: 0px" required="on">
                                                    <option>Año</option>
                                                    <?php
                                                        $anno_inicio = '2016';
                                                        $anno_actual = date('yy');
                                                        for ($i = $anno_inicio; $i <=$anno_actual ; $i++) {
                                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                                        }
                                                        ?>
                                                </select>
                                                <input type="text" name="id_medidor" placeholder="Codigo del Medidor">
                                                <button type="submit" class="btn btn-outline-primary" style="margin: -4px 0 0 0">Filtrar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endif



@if(!empty($_REQUEST['print']))
    <style>
        div#cabecera,.navbar,.btn,.title-reporte, select, input, form {display: none; }
    </style>
    <script>
        print();
        setTimeout(function () { $('.btn, .title-reporte,form').show(); }, 5000);
    </script>
@endif

<script>
    function imprimir(){
      var url = window.location;
       location.href = url+'&print=active';


    }
</script>




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



<style>
	body .val{
		font-size: 10px !important
	}

</style>

@endsection
