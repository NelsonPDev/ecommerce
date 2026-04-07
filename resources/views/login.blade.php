<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Iniciar Sesión - TechStore</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            color: #667eea;
            margin: 0;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.1);
        }
        .form-group.error input {
            border-color: #e74c3c;
        }
        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }
        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🔐 TechStore</h1>
            <p style="color: #999; margin: 5px 0 0 0;">Iniciar Sesión</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required autofocus>
                @error('correo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>

        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
        </div>
    </div>

    <script>
        // Función para forzar redirección con timestamp único
        function forceRedirect() {
            var timestamp = new Date().getTime();
            var dashboardUrl = '{{ route("dashboard") }}' + '?t=' + timestamp;
            console.log('Forzando redirección a:', dashboardUrl);
            window.location.replace(dashboardUrl);
        }

        // Verificar inmediatamente si hay mensaje de éxito
        @if(session('success'))
            console.log('Mensaje de éxito detectado, redirigiendo...');
            forceRedirect();
        @endif

        // Prevenir cache agresivamente
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for(let registration of registrations) {
                    registration.unregister();
                }
            });
        }

        // Limpiar todo tipo de cache
        window.addEventListener('load', function() {
            // Limpiar caches del navegador
            if ('caches' in window) {
                caches.keys().then(function(names) {
                    for (let name of names) {
                        caches.delete(name);
                    }
                });
            }

            // Limpiar localStorage y sessionStorage relacionados con login
            try {
                localStorage.removeItem('login_cache');
                sessionStorage.removeItem('login_cache');
            } catch (e) {
                console.log('Error limpiando storage:', e);
            }
        });

        // Prevenir navegación hacia atrás al login cuando ya estamos autenticados
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Página cargada desde cache, forzar recarga
                window.location.reload();
            }
        });

        // Verificación adicional cada 100ms por 2 segundos
        var checkCount = 0;
        var checkInterval = setInterval(function() {
            @if(session('success'))
                console.log('Verificación periódica: mensaje de éxito encontrado');
                clearInterval(checkInterval);
                forceRedirect();
            @endif

            checkCount++;
            if (checkCount > 20) { // 2 segundos
                clearInterval(checkInterval);
            }
        }, 100);
    </script>
</body>
</html>
