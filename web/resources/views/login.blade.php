<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Gestión de Alertas</title>
    
    <link rel="stylesheet" href="{{ asset('login.css') }}">
</head>
<body>

    <div class="login-container">
        <!-- Título principal -->
        <h1 class="login-title">Accede a tu sistema de<br>gestión de alertas</h1>

        <!-- Botón de autenticación con Google -->
        <a  href="{{ route('google.login') }}" class="btn-google" >
            <svg class="google-icon" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            Continuar con Google
        </a>

        <!-- Divisor "o" -->
        <div class="divider">o</div>

        <!-- Formulario Laravel -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Campo Correo Electrónico -->
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="input-control" 
                        placeholder="Ingresa un correo electrónico"  
                        title="Por favor, introduce un correo válido" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                </div>
                @error('email')
                    <span style="color: #e3342f; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Campo Contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-control" 
                        minlength="8"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                        title="Debe contener al menos 8 caracteres, incluyendo una letra mayúscula, una minúscula y un número."
                        placeholder="••••••••" 
                        required
                    >
                    <button type="button" class="toggle-password" id="hiddenpassword" aria-label="Mostrar/Ocultar contraseña">
                        <!-- Icono ojo tachado / ocultar contraseña -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span style="color: #e3342f; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Recordarme</label>
                
            </div>

            <!-- Botón de Inicio de Sesión -->
            <button type="submit" class="btn-submit">
                Iniciar sesión
            </button>
        </form>
    </div>

    <!-- Script para alternar visibilidad de la contraseña -->
    <script src="{{ asset('js/login.js') }}" ></script>
</body>
</html>