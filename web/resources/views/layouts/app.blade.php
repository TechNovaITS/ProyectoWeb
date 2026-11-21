<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sentinel - Control Central')</title>
    <!-- FontAwesome para los iconos -->
    <link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
      crossorigin="anonymous" 
      referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('layout.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body>

    <div class="admin-container">
        <!-- Fondo oscuro para cerrar el menú en móviles -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Menú Lateral Izquierdo -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-icon">
                    <img src="{{ asset('sentinel.png') }}" alt="Logo de sentinel" >

                </div>
                <div class="sidebar-brand">
                    <h1>Sentinel</h1>
                    <span>CONTROL CENTRAL</span>
                </div>
                <!-- Botón de cierre (X) visible solo en móviles -->
                <button class="close-sidebar" id="closeSidebar" aria-label="Cerrar menú">
                    <i class="fa-solid fa-xmark"></i>
                </button>
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
                            <a href="{{ route('alertas.index') }}" class="{{ request()->routeIs('alertas.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-bell"></i> Alertas
                            </a>
                        @endif
                    </li>
                    <li>
                        @if(auth()->user()->rol === 'Administrador' or auth()->user()->rol === 'Recovery')
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
                                <i class="fa-solid fa-chart-line"></i> Dashboards
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
                            <a href="{{ route('auditoria.actividad') }}" class="{{ request()->routeIs('auditoria.*') ? 'active' : '' }}">
                                <i class="fa-solid fa-chart-bar"></i> Auditoría
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

        <!-- Contenedor Derecho que incluye la barra móvil y el contenido dinámico -->
        <div class="main-wrapper">
            <!-- Barra superior móvil con botón hamburguesa -->
            <header class="mobile-header">
                <button class="menu-toggle" id="openSidebar" aria-label="Abrir menú">
                    <i class="fa-solid fa-bars"></i>
                </button>
                
            </header>

            <!-- Contenido Dinámico -->
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    @include('modals.modal_eliminar')    
    @livewireScripts
    <script src="{{ asset('app.js') }}" ></script>

    <!-- Script de control para el menú responsive -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const openBtn = document.getElementById('openSidebar');
            const closeBtn = document.getElementById('closeSidebar');

            function toggleSidebar() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            }

            if (openBtn) openBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>