<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class GoogleController extends Controller
{
    
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // Obtener los datos del usuario desde Google
    $googleUser = Socialite::driver('google')->user();

    // Buscar si el usuario ya existe en la base de datos por su email
    $user = Usuario::where('email', $googleUser->getEmail())->first();

    // Si el usuario no existe, impedir el acceso
    if (!$user) {
        return redirect()->route('login')->withErrors([
            'email' => 'No existe una cuenta registrada con este correo de Google.'
        ]);
    }

    // Iniciar sesión con el usuario existente
    Auth::login($user);

    return redirect()->intended('dashboard');
    }
}