<!DOCTYPE html>
<html dir="ltr" lang="es">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="Concursos MSPBS" />
    <meta name="description" content="Concurso" />
    <meta name="robots" content="noindex,nofollow" />
    <title>@yield("titulo", "Concurso")</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset("assets/assets/images/favicon.png")}}"/>
    <!-- Custom CSS -->
    <link href="{{asset("assets/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet">
    <!-- Custom CSS for Pages -->
    @yield('style')
    <link href="{{asset("assets/dist/css/style.min.css")}}" rel="stylesheet" />
    <link href="{{asset("assets/concursos/css/concursos.css")}}" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        @include('layout.header')
        @include('layout.aside')


        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            {{-- @include('layout.bread') --}}
            <div class="container-fluid">
                @yield('contenido')
                {{-- @include('layout.content') --}}
            </div>

            @include('layout.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset("assets/assets/libs/jquery/dist/jquery.min.js")}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset("assets/assets/libs/popper.js/dist/umd/popper.min.js")}}"></script>
    <script src="{{asset("assets/assets/libs/bootstrap/dist/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js")}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset("assets/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js")}}"></script>
    <script src="{{asset("assets/assets/extra-libs/sparkline/sparkline.js")}}"></script>
    <!--Wave Effects -->
    <script src="{{asset("assets/dist/js/waves.js")}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset("assets/dist/js/sidebarmenu.js")}}"></script>
    <!--JQuery Validation -->
    <script src="{{asset("assets/assets/libs/jquery-validation/dist/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/assets/libs/jquery-validation/dist/localization/messages_es.js")}}"></script>
    <!--DataTables -->
    <script src="{{asset("assets/assets/extra-libs/DataTables/datatables.min.js")}}"></script>
    <!--Moment-->
    <script src="{{asset("assets/assets/libs/moment/min/moment.min.js")}}"></script>
    <script src="{{asset("assets/assets/extra-libs/DataTables/datetime-moment.js")}}"></script>
    <!--Pluggins for Pages -->
    @yield('scriptsPluggins')
    <!--Custom JavaScript -->
    <script src="{{asset("assets/concursos/js/concursos.js")}}"></script>
    <script src="{{asset("assets/dist/js/custom.min.js")}}"></script>
    <!--Script for Pages -->
    @yield('scripts')
  </body>
</html>
