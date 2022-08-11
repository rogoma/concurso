@extends('auth.layout')

@section('contenido')
<!-- ============================================================== -->
<!-- Login box.scss -->
<!-- ============================================================== -->
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
    <div class="auth-box bg-dark border-top border-secondary">
        <div id="loginform">
            <div class="text-center p-t-20 p-b-20">
                <span class="db"><img src="{{asset("assets/assets/images/logo-mspbs.png")}}" alt="logo" height="75" /></span>
            </div>
            <!-- Form -->
            @if ($mensaje = session("mensaje"))
                <x-alerts tipo="success" :mensaje="$mensaje" />
            @endif
            @if($errors->any())
                <x-alerts tipo="danger" :mensaje="$errors"/>
            @endif
            <div class="row">
                <br/>
            </div>
            <form action="{{route("login")}}" class="form-horizontal m-t-20" id="loginform" method="POST">
                @csrf
                <div class="row p-b-30">
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" name="ci" id="ci" class="form-control form-control-lg" placeholder="Cédula de Identidad" aria-label="Cédula de Identidad" aria-describedby="basic-addon1" required autofocus autocomplete="ci">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                            </div>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required autocomplete="current-password">
                        </div>
                    </div>
                </div>
                <!-- div class="g-recaptcha" data-sitekey="6LdMQVocAAAAAHpq5buGCCmLW6Rulm5IOSiOtPy2"></div -->
                <div class="row border-top border-secondary">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="p-t-20">
                                <button class="btn btn-info" type="button" onclick=" location.href='{{url("forgot-password")}}'"><i class="fa fa-lock m-r-5"></i> Olvidó contraseña</button>
                                <button class="btn btn-primary" type="button" onclick=" location.href='{{url("register")}}'"><i class="fas fa-sign-in-alt m-r-5"></i> Registro</button>
                                <button class="btn btn-success float-right" type="submit">Entrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Login box.scss -->
<!-- ============================================================== -->
@endsection
