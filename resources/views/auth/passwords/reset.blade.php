@extends('auth.layout')

@section('contenido')
<!-- ============================================================== -->
<!-- Reset Passwd box.scss -->
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
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <x-alerts tipo="danger" :mensaje="$errors"/>
            @endif
            <form class="form-horizontal m-t-20" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="row p-b-30">
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
                            </div>
                            <input id="email" type="email" class="form-control form-control-lg is-invalid" name="email" value="{{ $request->email }}" placeholder="Correo Electrónico" aria-label="Correo Electrónico" aria-describedby="basic-addon1" required autocomplete="email" autofocus>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                            </div>
                            <input type="password" name="password" id="password" class="form-control form-control-lg is-invalid" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required autocomplete="new-password">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                            </div>
                            <input id="password-confirm" type="password" class="form-control form-control-lg" placeholder=" Confirmar Password" aria-label="Password" aria-describedby="basic-addon1" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="row m-t-1 p-t-20 border-top border-secondary">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="col-12">
                                <button class="btn btn-success float-right" type="submit">{{ __('Actualizar') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
