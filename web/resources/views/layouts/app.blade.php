<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sentinel Ops - Control Central')</title>
    <!-- FontAwesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('layout.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body>

    <div class="admin-container">
        <!-- Menú Lateral Izquierdo -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="sidebar-brand">
                    <h1>Sentinel Ops</h1>
                    <span>CONTROL CENTRAL</span>
                </div>
            </div>

            <div class="sidebar-section-title">
                @if(auth()->user()->rol === 'Administrador')
                    ADMINISTRACIÓN
                @elseif(auth()->user()->rol === 'Operador')
                    OPERADOR
                @elseif (auth()->user()->rol === 'Auditor')
                    AUDITOR
                @elseif (auth()->user()->rol === 'Recovery')
                    RECUPERACION
                @endif
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        @if(auth()->user()->rol === 'Administrador' or auth()->user()->rol === 'Operador')
                            <a href="#" class="{{ request()->routeIs('alertas.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-bell"></i> Alertas
                            </a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->rol === 'Administrador')
                            <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-user"></i> Usuarios
                            </a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->rol === 'Administrador')
                            <a href="{{ route('departamentos.index') }}" class="{{ request()->routeIs('departamentos.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> Departamentos
                            </a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->rol === 'Administrador')
                            <a href="{{ route('dashboards.index') }}" class="{{ request()->routeIs('dashboards.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-dashboard"></i> Dashboards
                            </a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->rol === 'Administrador' or auth()->user()->rol === 'Operador')
                            <a href="{{ route('dispositivos.index') }}" class="{{ request()->routeIs('dispositivos.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-server"></i> Dispositivos
                            </a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->rol === 'Administrador' or auth()->user()->rol === 'Auditor')
                            <a href="#" class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-chart-bar"></i> Reportes
                            </a>
                        @endif
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-power-off"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- Contenido Dinámico -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @include('modals.modal_eliminar')    
    @livewireScripts
    <script nonce="{{ app('csp-nonce') }}" src="{{ asset('app.js') }}" ></script>
</body>
</html>