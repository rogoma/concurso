<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Concursos</span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{route('concursos')}}" class="sidebar-link"><i class="fas fa-hand-point-right"></i><span class="hide-menu"> Abiertos </span></a></li>
                        @if(session('role_slug') == 'postulantes')
                        <li class="sidebar-item">
                            <a href="{{route('concursos.evaluacion')}}" class="sidebar-link"><i class="fas fa-tasks"></i><span class="hide-menu"> En Evaluación </span></a></li>
                        @endif
                    </ul>
                </li>
                @if(session('role_slug') == 'postulantes')
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-folder-account"></i><span class="hide-menu">Curriculum Virtual </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{route('personales')}}" class="sidebar-link"><i class="fas fa-address-book"></i><span class="hide-menu"> Personales </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('academicos')}}" class="sidebar-link"><i class="fas fa-book"></i><span class="hide-menu"> Académicos </span></a></li>
                        <li class="sidebar-item">
                                <a href="{{route('capacitacion')}}" class="sidebar-link"><i class="fas fa-dna"></i><span class="hide-menu"> Capacitaciones </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('laborales')}}" class="sidebar-link"><i class="fas fa-building"></i><span class="hide-menu"> Laborales </span></a></li>
                    </ul>
                </li>
                @endif
                @if(session('role_slug') == 'validador')
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-checkbox-marked-circle-outline"></i><span class="hide-menu">Validación </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{route('documental')}}" class="sidebar-link"><i class="mdi mdi-checkbox-multiple-marked"></i><span class="hide-menu"> Evaluación Documental </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('curricular')}}" class="sidebar-link"><i class="mdi mdi-file-check"></i><span class="hide-menu"> Evaluación Curricular </span></a></li>
                    </ul>
                </li>
                @endif
                @if(session('role_slug') == 'administrador')
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">Sistema </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{route('roles')}}" class="sidebar-link"><i class="mdi mdi-account-settings-variant"></i><span class="hide-menu"> Roles </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('usuarios')}}" class="sidebar-link"><i class="mdi mdi-account"></i><span class="hide-menu"> Usuarios </span></a></li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-server"></i><span class="hide-menu">Configuración </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="{{route('tipodoc')}}" class="sidebar-link"><i class="mdi mdi-file-multiple"></i><span class="hide-menu"> Tipos de Documentos </span></a></li>
                        <li class="sidebar-item">
                                <a href="{{route('rechazodoc')}}" class="sidebar-link"><i class="mdi mdi-sim-off"></i><span class="hide-menu"> Tipos de Rechazos </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('cargos')}}" class="sidebar-link"><i class="mdi mdi-animation"></i><span class="hide-menu"> Cargos </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('concourse')}}" class="sidebar-link"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu"> Concursos </span></a></li>
                        <li class="sidebar-item">
                            <a href="{{route('examen.list')}}" class="sidebar-link"><i class="mdi mdi-library"></i><span class="hide-menu"> Examenes </span></a></li>
                    </ul>
                </li>
                @endif
                <!--li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="pages-elements.html" aria-expanded="false"><i class="mdi mdi-pencil"></i><span class="hide-menu">Elements</span></a></li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-account-key"></i><span class="hide-menu">Authentication </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="authentication-login.html" class="sidebar-link"><i class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Login </span></a></li>
                        <li class="sidebar-item">
                            <a href="authentication-register.html" class="sidebar-link"><i class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Register </span></a></li>
                    </ul>
                </li-->
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
