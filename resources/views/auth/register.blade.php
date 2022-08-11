@extends('auth.layout')

@section('contenido')
        <!-- ============================================================== -->
        <!-- Register box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                <div>
                    <div class="text-center p-t-20 p-b-20">
                        <span class="db"><img src="{{asset("assets/assets/images/logo-mspbs.png")}}" alt="logo" height="75" /></span>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <x-alerts tipo="danger" :mensaje="$errors"/>
                    @endif
                    <div class="row">
                        <br/>
                    </div>
                    <!-- Form -->
                    <form class="form-horizontal m-t-20" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row p-b-30">
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="ci" class="form-control form-control-lg" placeholder="Cédula de Identidad" aria-label="Cédula de Identidad" aria-describedby="basic-addon1" required autocomplete="ci" autofocus value="{{ old('ci') }}">
                                </div>
                                <!-- email -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
                                    </div>
                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Correo Electrónico" aria-label="Correo Electrónico" aria-describedby="basic-addon1" required autocomplete="email" value="{{ old('email') }}">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder=" Confirmar Password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row m-t-1 p-t-20 border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-info" onclick="location.href='{{url("login")}}'">Regresar</button>
                                        <button class="btn btn-success float-right" type="submit">Registrarse</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Register box.scss -->
        <!-- ============================================================== -->
@endsection
