@extends('auth.layout')

@section('contenido')
<!-- ============================================================== -->
<!-- Verify box.scss -->
<!-- ============================================================== -->
<div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
    <div class="auth-box bg-dark border-top border-secondary">
        <div id="loginform">
            <div class="text-center p-t-20 p-b-20">
                <span class="db"><img src="{{asset("assets/assets/images/logo-mspbs.png")}}" alt="logo" height="75" /></span>
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
            <!-- Form -->
            <div class="row m-t-20">
                <form method="POST" action="{{ route('verification.send') }}" class="form-horizontal m-t-20" id="loginform">
                    @csrf
                    <div class="text-center">
                        <h4 class="text-white">
                            {{ __('Antes de continuar, verifique su correo electrónico para ver si hay un enlace de verificación.') }}
                            {{ __('Si no recibió el correo electrónico') }}
                        </h4>
                    </div>
                    <div class="row m-t-20 p-t-20 border-top border-secondary">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success float-right">{{ __('Reenviar correo') }}</button>.
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- Verify box.scss -->
<!-- ============================================================== -->
@endsection
