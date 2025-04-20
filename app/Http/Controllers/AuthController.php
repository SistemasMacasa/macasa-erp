<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Validamos que sea un usuario interno para iniciar sesión en el ERP
        $usuario = Usuario::where('username', $request->username)
            ->where('tipo', 'ERP')
            ->whereNull('id_cliente')
            ->first();

        if (!$usuario || !\Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['login_error' => 'Credenciales incorrectas.']);
        }

        Auth::login($usuario);
        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
