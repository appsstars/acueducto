@extends('layouts.admin')

@section('content')
<div class="container home">
    <div class="row">

      <div class="col-md-3 col-sm-4" style="display: none;">
          <div class="wrimagecard wrimagecard-topimage">
               <a href="{{url('app/pago')}}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                 <img src="{{url('img/iconos/icono-ver-pagos.png')}}" alt="">
               </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Ver pagos
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>


        <div class="col-md-3 col-sm-4">
          <div class="wrimagecard wrimagecard-topimage">
              <a href="{{url('app/pago/create')}}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                 <img src="{{url('img/iconos/icono-pagos.png')}}" alt="">
               </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Agregar pagos
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>



        <div class="col-md-3 col-sm-4">
          <div class="wrimagecard wrimagecard-topimage">
              <a href="{{url('app/medicion')}}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                  <img src="{{url('img/iconos/icono-ver-mediciones.png')}}" alt="">
                </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Ver facturas
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>

        <div class="col-md-3 col-sm-4">
          <div class="wrimagecard wrimagecard-topimage">
              <a href="{{url('app/medicion/create')}}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                 <img src="{{url('img/iconos/icono-agregar-mediciones.png')}}" alt="">
               </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Crear factura
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>





        <div class="col-md-3 col-sm-4">
          <div class="wrimagecard wrimagecard-topimage">
              <a href="{{ url('app/clientes') }}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                  <img src="{{url('img/iconos/icono-clientes.png')}}" alt="">
                </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Clientes
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>


        <div class="col-md-3 col-sm-4">
          <div class="wrimagecard wrimagecard-topimage">
              <a href="{{ url('app/credito') }}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                  <img src="{{url('img/iconos/icono-reportes.png')}}" alt="">
                </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Otros cobros
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>


        <div class="col-md-3 col-sm-4">
          <div class="wrimagecard wrimagecard-topimage">
              <a href="{{url('app/reportes')}}">
              <div class="wrimagecard-topimage_header" style="background-color: rgba(22, 160, 133, 0.1)">
                <center>
                  <img src="{{url('img/iconos/icono-reportes.png')}}" alt="">
                </center>
              </div>
              <div class="wrimagecard-topimage_title">
                <h4 class="text-center">Reportes
                <div class="pull-right badge" id="WrControls"></div></h4>
              </div>
            </a>
          </div>
        </div>








    </div>
</div>
@endsection
