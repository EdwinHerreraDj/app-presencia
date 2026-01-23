<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthenticatedSessionController extends Controller
{

    public function index()
    {
        return view('auth.signin');
    }
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.signin');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        // Intentar autenticar al usuario
        if (!Auth::attempt($credentials, $request->filled('remember'))) {
            return redirect()->back()
                ->withErrors(['login' => 'Las credenciales proporcionadas no son válidas.'])
                ->withInput($request->only('email', 'remember'));
        }

        // Regenerar sesión para evitar fijación de sesión
        $request->session()->regenerate();

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Guardar datos en sesión
        session([
            'user_id'    => $user->id,
            'user_role'  => $user->rol,
            'user_name'  => $user->name,
            'user_email' => $user->email,
        ]);

        // Redirigir según el rol
        return match ($user->rol) {
            'encargado' => redirect()->route('terminal.fichaje'),

            'admin', 'super_admin' => redirect()->route('empresas'),

            'empleado' => redirect()->route('fichaje'),

            default => redirect()->route('auth.signin'),
        };
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
