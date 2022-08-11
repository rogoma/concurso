@extends('auth.layout')

@section('contenido')
<!-- ============================================================== -->
<!-- Forgot box.scss -->
<!-- ============================================================== -->
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
    <div class="auth-box bg-dark border-top border-secondary">
        <div>
            <div class="text-center p-t-20 p-b-20">
                <span class="db"><img src="{{asset("assets/assets/images/logo-mspbs.png")}}" alt="logo" height="75" /></span>
            </div>
            <div class="row">
                <br/>
            </div>
            <div class="text-center">
                <h4 class="text-white">Escriba su email.</h4>
            </div>
            <div class="row">
                <br/>
            </div>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <x-alerts tipo="danger" :mensaje="$errors"/>
            @endif
            <div class="row m-t-20">
                <!-- Form -->
                <form class="col-12" action="{{ route('password.request') }}" method="POST">
                    @csrf
                    <!-- email -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
                        </div>
                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Correo Electrónico" aria-label="Correo Electrónico" aria-describedby="basic-addon1" autocomplete="email" autofocus>
                    </div>
                    <!-- pwd -->
                    <div class="row m-t-20 p-t-20 border-top border-secondary">
                        <div class="col-12">
                            <button class="btn btn-info" onclick="location.href='{{ url("login") }}'" type="button">Regresar</button>
                            <button class="btn btn-success float-right" type="submit">Recuperar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Forgot box.scss -->
<!-- ============================================================== -->
@endsection
