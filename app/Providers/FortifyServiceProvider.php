<?php

namespace App\Providers;

use App\Models\Usuario;
use App\Models\Configuracione;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Responses\RegisterResponse;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

use phpDocumentor\Reflection\PseudoTypes\True_;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


        //Fortify::ignoreRoutes();

        /*app()->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('login');
            }
        });*/

        app()->bind(
            RegisterResponseContract::class,
            RegisterResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.forgot');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset', ['request' => $request]);
        });

        /*
        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });*/

        Fortify::authenticateUsing(function (Request $request) {
            $usuario = Usuario::with('perfil')
                        ->where('ci', $request->ci)
                        ->first();
            //dd($usuario);
            if ($usuario) {
                if (($usuario->email_verified_at !== null
                        && $usuario->perfil->postulante == 'I')
                        || $usuario->ci === 'admin') {
                    if ($usuario->activo !== true) {
                        Usuario::findOrFail($usuario->id)->update(array('activo' => true));
                    }
                    if ($usuario && Hash::check($request->password, $usuario->password)) {
                        $rol = $usuario->rol()->first();
                        if ($rol) {
                            $request->session()->put('usuario_id', $usuario->id);
                            $request->session()->put('role_slug', $rol->slug);
                            $request->session()->put('role_id', $rol->id);
                            $request->session()->put('ci', $usuario->ci);
                            if ($usuario->perfil->nombres !== null) {
                                $request->session()->put('perfil', true);
                            } else {
                                $request->session()->put('perfil', false);
                            }
                            return $usuario;
                        }
                        return false;
                    }
                } else {
                    $request->session()->put('perfil', $usuario->perfil->postulante);
                    return $usuario;
                }
            }
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ci.$request->ip());
        });

        /*
         RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3)->by($request->ci.$request->ip())
                ->response(function () {
                    return redirect()->route('login')
                        ->with(['errors' => ['throttle' => 'Too many login attempts. Please try again in :seconds seconds.']]);

                });
        });
         */


        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('idusuario'));
        });


    }
}
