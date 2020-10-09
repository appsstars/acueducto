
@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <!-- <a href="{{url('app')}}" class="btn btn-outline-info btn-sm btn-block">Volver</a> -->
                    <a href="{{url('app')}}" style="text-decoration: none;"><i class="fas fa-angle-double-left icon-return"> </i><h5 class="atras">atras</h5></a>
                </div>
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    <a href="#modal-search" data-toggle="modal" class="btn" style="font-size: 11px; background-color:#cede2c;color:grey">GENERAR RECIBOS</a>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card text-center">
                                    <div class="alert alert-dismissable" role="alert">
                                            <strong>Advertencia!</strong> Seleccione un un Mes  para generar un listado de facturas.
                                    </div>

                                    <form action="" method="get">
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
                                        <button type="submit" class="btn btn-outline-primary btn-sm" style="margin: -4px 0 0 0">Filtrar</button>
                                    </form>
                                    @if(!empty($_REQUEST['mes']))
                                        <div class="tabla">
                                            <table class="table table-bordered table-striped" id="tabla-">
                                                <thead>

                                                    <tr>
                                                        <td>Cod_medidor</td>
                                                        <td>Periodo</td>
                                                        <td>Consumo</td>
                                                        <td>Otros cobros</td>
                                                        <td>Valor factura</td>
                                                        <td>Total a pagar</td>
                                                        <td>Opcion</td>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($facturas as $f)
                                                        <tr>
                                                            <td> {{$f->id_medidor}} </td>
                                                            <td> {{$f->periodo}} </td>
                                                            <td> {{$f->consumo}} </td>
                                                            <td>

                                                                <script>
                                                                    var valor = 0;
                                                                    var total_facturacion = <?php echo $f->total_pagar?>;
                                                                    var credito =  @json($credito);
                                                                    var filtro = credito.filter(dato => dato.id_medidor == <?php echo  $f->id_medidor;?> );
                                                                    if(filtro.length>0){
                                                                        valor = filtro[0]['valor'];
                                                                        total_facturacion = parseInt(total_facturacion) + parseInt(valor);
                                                                    }
                                                                    document.write(valor);
                                                                </script>
                                                            </td>

                                                            <td> {{$f->total_pagar}} </td>
                                                            <td>
                                                                <?php
                                                                        $total_facturacion =  "<script>document.write(total_facturacion)</script>";
                                                                        echo $total_facturacion;
                                                                    ?>
                                                            </td>
                                                            <td style="width:3%">
                                                                <form action="" method="get" >
                                                                    <input type="hidden" name="id_medidor" value="{{$f->id_medidor}}">
                                                                    <input type="hidden" name="periodo" value="{{$f->periodo}}">
                                                                    <button type="submit" class="btn btn-outline-primary btn-sm" style="margin: -4px 0 0 0;">Descargar</button>
                                                                </form>


                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                        
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            
        </div>
    </div>


    <!--- modal select date ---->



  <div class="modal fade" id="modal-search" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Añadir lectura</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
                <?php 
                    $mes = date('m');
                    $anno = date('yy');

                    $mes = $mes - 1;

                    if($mes==0){
                          $anno = $anno - 1;
                          $mes = 12;
                    }

                   



                ?>

                <select name="mes_search" onchange="get_facturas()" class="form-control">
                    <option>Seleccionar mes...</option>
                    <?php 
                        for ($i=1; $i <=2; $i++) { 
                            if($mes>=1 && $mes<=9){$mes = '0'.$mes;}
                            echo "<option value='".$mes."'>".$mes."</option>";
                            $mes = $mes + 1;
                        }

                     ?>
                </select>


                
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
          <a href="" id="search">GENERAR</a>
        </div>
      </div>
    </div>
  </div>



        

    <!----- end section -->

<script src="{{asset('js/controller/FacturaController.js')}}"></script>
@include('medicion.modal-recibo')
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
	function generar_recibo(id){
		$('input[name=id_cliente]').val(id);
		$('#form-create').modal('show');
	}
</script>

<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#tabla-').DataTable();
    });

    function get_facturas(){
        var mes = $('select[name=mes_search]').val();
        var url = "generar_facturas?print=active&mes="+mes;
        $('#search').attr('href',url);
    }
</script>



@endsection
