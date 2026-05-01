# 📊 DIAGRAMAS Y FLUJOS - Mini Proyecto 3

## 🔐 FLUJO DE AUTENTICACIÓN 2FA

```
┌─────────────────────────────────────────────────────────────────┐
│                    LOGIN 2FA COMPLETO                           │
└─────────────────────────────────────────────────────────────────┘

FASE 1: CREDENTIALS
┌──────────────────────┐
│ GET /login           │
│ → view('login')      │
└──────┬───────────────┘
       │
       │ POST /login (correo + clave)
       │ LoginUsuarioRequest validating
       │
       ▼
┌────────────────────────────────────┐
│ LoginController::login()           │
│ 1. Auth::validate($credentials)    │
│ 2. ✅ Válido                       │
│    ├─ Log: "Login correcto (fase 1)"
│    ├─ $usuario = Usuario::find()   │
│    │                                │
│    └─► GENERA CÓDIGO 2FA            │
│        TwoFactorCodeService::generateFor()
│        └─ CodigoVerificacion::create([
│             'usuario_id' => $id,
│             'codigo' => '123456',  
│             'expiracion' => now()->addMinutes(5)
│          ])
│        └─ Log: "Código 2FA generado"
│                                     │
│    └─► ENVÍA EMAIL                  │
│        Mail::to()->send(CodigoVerificacionMail())
│                                     │
│    └─► SESSION                      │
│        session->put('autenticacion.usuario_2fa', $usuario->id)
│                                     │
│ 3. ❌ Inválido                      │
│    └─ Log: "Login fallido"          │
│    └─ redirect(login)->withErrors   │
└────────────────┬───────────────────┘
                 │
                 │ ✅ Redirige a
                 ▼
┌─────────────────────────────────┐
│ GET /verificacion-2fa           │
│ → view('auth.two-factor')       │
└──────┬──────────────────────────┘
       │
       │ Usuario recibe email con código
       │ Ingresa código en formulario
       │ POST /verificacion-2fa (codigo)
       │ VerifyTwoFactorCodeRequest validating
       │
       ▼
┌────────────────────────────────────┐
│ LoginController::verifyTwoFactorCode()
│                                     │
│ $usuario = session->get(...)        │
│ $resultado = TwoFactorCodeService::verify(
│    $usuario, 
│    $request->codigo
│ )                                   │
│                                     │
│ Casos:                              │
│ 1. ✅ 'valid'                       │
│    ├─ session->forget(...)          │
│    ├─ Auth::login($usuario)         │
│    ├─ session->regenerate()         │
│    ├─ Log: "Código validado correctamente"
│    └─► redirect('/dashboard')       │
│                                     │
│ 2. ❌ 'invalid'                     │
│    ├─ Log: "Código inválido"        │
│    └─ redirect(back())->withErrors  │
│                                     │
│ 3. ❌ 'expired'                     │
│    ├─ session->forget(...)          │
│    ├─ Log: "Código expirado"        │
│    └─ redirect(login)->withErrors   │
└────────────────────────────────────┘
       │
       │ ✅ Acceso concedido
       ▼
┌─────────────────────────────────┐
│ GET /dashboard                  │
│ → view('dashboard')             │
│ Usuario AUTENTICADO ✅          │
└─────────────────────────────────┘

OPCIÓN: REENVIAR CÓDIGO
┌──────────────────────────────────┐
│ POST /verificacion-2fa/reenviar  │
│ → TwoFactorCodeService::generateFor()
│    (limpia código anterior)
│ → Mail::to()->send(Mail nuevo)
└──────────────────────────────────┘
```

---

## 📁 FLUJO DE ARCHIVOS (DISCO PÚBLICO vs PRIVADO)

```
┌────────────────────────────────────────────────────────────┐
│            ALMACENAMIENTO DE ARCHIVOS                      │
└────────────────────────────────────────────────────────────┘

DISCO PÚBLICO (storage/app/public)
├─ Accesible vía: /storage/...
├─ URL pública: $product->fotoUrls()
├─ Uso: PRODUCTO FOTOS
│
└─ ProductoSeeder:
   └─ foreach ($vendedor) {
        foreach (range 1..3) {
            $fotos = create SVG files
            Producto::create(['fotos' => $fotos])  ← JSON array
        }
      }
   
   Rutas guardadas:
   ├─ "productos/producto-1-1.svg"
   ├─ "productos/producto-1-2.svg"
   └─ Storage::disk('public')->url($path)
      → https://app/storage/productos/producto-1-1.svg

DISCO PRIVADO (storage/app/private)
├─ NO accesible directo por URL
├─ Acceso SOLO mediante controlador autorizado
├─ Uso: VENTA TICKETS
│
└─ VentaController::storeTicket():
   └─ $request->file('ticket')->store('tickets', 'private')
      → Guarda en: storage/app/private/tickets/...

   Acceso AUTENTICADO:
   └─ GET /ventas/{venta}/ticket
      ├─ VentaPolicy::viewTicket()
      │  ├─ Solo $user->esGerente()
      │  ├─ O $venta->cliente_id === $user->id
      │  └─ O $venta->vendedor_id === $user->id
      │
      └─ VentaController::showTicket($venta)
         ├─ $this->authorize('viewTicket', $venta)
         ├─ Storage::disk('private')->exists($venta->ticket)
         └─ Storage::disk('private')->response($venta->ticket)
            → Descarga archivo con headers correctos

VISIBILIDAD COMPARADA
┌────────────┬───────────────┬────────────────┐
│ Tipo       │ Público       │ Privado        │
├────────────┼───────────────┼────────────────┤
│ Ubicación  │ /storage/...  │ storage/app... │
│ URL Pública│ ✅ SÍ         │ ❌ NO          │
│ Directo    │ ✅ SÍ         │ ❌ NO          │
│ Controller │ ✅ SÍ         │ ✅ Autorizado  │
│ Policy     │ ❌ No         │ ✅ viewTicket  │
└────────────┴───────────────┴────────────────┘
```

---

## 🔒 FLUJO DE POLÍTICAS (POLICIES)

```
┌─────────────────────────────────────────────────┐
│       CONTROL DE ACCESO CON POLICIES            │
└─────────────────────────────────────────────────┘

VENTA (VentaPolicy)
┌──────────────────────────────────────┐
│ Acción          │ Admin │ Gerente │ CLI │ VEN
├──────────────────────────────────────┤
│ viewAny()       │  ✅   │   ✅    │  ✅  │  ✅
│ view(venta)     │  ✅   │   ✅    │  ✅* │  ✅*
│ create()        │  ✅   │   ❌    │  ✅  │  ❌
│ update()        │  ✅   │   ✅    │  ❌  │  ❌
│ delete()        │  ✅   │   ❌    │  ❌  │  ❌
│ validate()      │  ❌   │   ✅    │  ❌  │  ❌
│ viewTicket()    │  ✅   │   ✅    │  ✅* │  ✅*
└──────────────────────────────────────┘
* Solo su propia venta

VentaPolicy::view($user, $venta)
```
if ($user->esAdministrador() || $user->esGerente()) {
    return true;  // Ve todas
}
return $venta->cliente_id === $user->id || 
       $venta->vendedor_id === $user->id;  // Ve sus propias
```

VentaPolicy::viewTicket($user, $venta)
```
return $user->esGerente() || 
       $venta->cliente_id === $user->id || 
       $venta->vendedor_id === $user->id;
```

PRODUCTO (ProductoPolicy)
┌──────────────────────────────────────┐
│ Acción          │ Admin │ Gerente │ VEN
├──────────────────────────────────────┤
│ viewAny()       │  ✅   │   ✅    │  ✅
│ view()          │  ✅   │   ✅    │  ✅
│ create()        │  ✅   │   ✅    │  ✅
│ update()        │  ✅   │   ✅    │  ✅*
│ delete()        │  ✅   │   ✅    │  ✅*
│ buy()           │  ❌   │   ❌    │  ❌
└──────────────────────────────────────┘
* Solo propios

CATEGORIA (CategoriaPolicy)
┌──────────────────────────────────────┐
│ Acción          │ Admin │ Gerente
├──────────────────────────────────────┤
│ viewAny()       │  ✅   │   ✅
│ view()          │  ✅   │   ✅
│ create()        │  ✅   │   ✅
│ update()        │  ✅   │   ✅
│ delete()        │  ✅   │   ✅
└──────────────────────────────────────┘

USUARIO (UsuarioPolicy)
┌──────────────────────────────────────┐
│ Acción          │ Admin │ Gerente │ SEL
├──────────────────────────────────────┤
│ viewAny()       │  ✅   │   ✅    │  ❌
│ view()          │  ✅   │   ✅*   │  ✅**
│ create()        │  ✅   │   ❌    │  ❌
│ update()        │  ✅   │   ✅*   │  ✅**
│ delete()        │  ✅***│  ❌    │  ❌
└──────────────────────────────────────┘
* Solo clientes  ** Solo self  *** No self

FLUJO DE AUTORIZACIÓN EN CONTROLLER
┌──────────────────────────────────────┐
│ VentaController::show($venta)        │
│                                      │
│ 1. $this->authorize('view', $venta)  │
│    ↓                                 │
│ 2. VentaPolicy::view($user, $venta)  │
│    ├─ if esAdministrador() → true    │
│    ├─ if esGerente() → true          │
│    ├─ if cliente_id === user→true    │
│    ├─ if vendedor_id === user→true   │
│    └─ else → false                   │
│    ↓                                 │
│ 3. ✅ Permitido → mostrar venta      │
│    ❌ Denegado → error 403           │
└──────────────────────────────────────┘

FLUJO DE AUTORIZACIÓN EN VISTA
┌──────────────────────────────────────┐
│ @can('update', $venta)               │
│    <button> Editar Venta </button>   │
│ @endcan                              │
│                                      │
│ @can('viewTicket', $venta)           │
│    <a href="/ticket">Ver Ticket</a>  │
│ @endcan                              │
└──────────────────────────────────────┘
```

---

## 📧 FLUJO DE NOTIFICACIONES (MAILABLES)

```
┌────────────────────────────────────────────────┐
│        ENVÍO DE CORREOS ELECTRÓNICOS           │
└────────────────────────────────────────────────┘

MAILABLE 1: CÓDIGO 2FA
┌────────────────────────────────────┐
│ LoginController::login()           │
│                                    │
│ Mail::to($usuario->correo)->send(  │
│    new CodigoVerificacionMail(     │
│        $usuario,                   │
│        $codigoVerificacion         │
│    )                               │
│ )                                  │
│                                    │
│ CodigoVerificacionMail:            │
│ ├─ Asunto: "Tu código de verificación"
│ ├─ Vista: emails/codigo-verificacion.blade.php
│ ├─ Variables:                      │
│ │  ├─ $usuario                    │
│ │  ├─ $codigoVerificacion         │
│ │  └─ Código de 6 dígitos         │
│ │  └─ Tiempo: 5 minutos           │
│ └─ Destinatario: usuario->correo  │
└────────────────────────────────────┘

MAILABLE 2: VENTA VALIDADA (VENDEDOR)
┌────────────────────────────────────┐
│ VentaController::validateSale()    │
│                                    │
│ Mail::to($venta->vendedor->correo)->send(
│    new VentaValidadaVendedorMail($venta)
│ )                                  │
│                                    │
│ VentaValidadaVendedorMail:         │
│ ├─ Asunto: "Una venta de tu producto fue validada"
│ ├─ Vista: emails/venta-validada-vendedor.blade.php
│ ├─ Variables: $venta con relaciones carguidas
│ │  ├─ Producto: $venta->producto->nombre
│ │  ├─ Comprador: $venta->cliente->nombre
│ │  ├─ Monto: $venta->total
│ │  ├─ Fecha: $venta->fecha
│ │  └─ Contacto: $venta->cliente->correo
│ └─ Destinatario: $venta->vendedor->correo
└────────────────────────────────────┘

MAILABLE 3: VENTA VALIDADA (COMPRADOR)
┌────────────────────────────────────┐
│ VentaController::validateSale()    │
│                                    │
│ Mail::to($venta->cliente->correo)->send(
│    new VentaValidadaCompradorMail($venta)
│ )                                  │
│                                    │
│ VentaValidadaCompradorMail:        │
│ ├─ Asunto: "Tu compra ha sido validada"
│ ├─ Vista: emails/venta-validada-comprador.blade.php
│ ├─ Variables: $venta con relaciones
│ │  ├─ Producto: $venta->producto
│ │  ├─ Vendedor: $venta->vendedor->nombre
│ │  ├─ Email vendedor: $venta->vendedor->correo
│ │  ├─ Monto: $venta->total
│ │  └─ Instrucciones: contactar al vendedor
│ └─ Destinatario: $venta->cliente->correo
└────────────────────────────────────┘

FLUJO COMPLETO DE VALIDACIÓN
┌──────────────────────────────────────┐
│ Gerente accede: /ventas              │
│ Ve: Venta PENDIENTE                  │
│ Click: "Validar"                     │
│        ↓                             │
│ POST /ventas/{venta}/validar         │
│        ↓                             │
│ VentaController::validateSale()      │
│        ├─ Valida Policy              │
│        ├─ Actualiza: estado=validada │
│        │           validada_at=now   │
│        │           validada_por=user │
│        ├─ Envía MAIL VENDEDOR        │
│        ├─ Envía MAIL COMPRADOR       │
│        └─ Log: "Venta validada"      │
│        ↓                             │
│ ✅ Redirect atrás con suceso         │
│                                      │
│ Resultado:                           │
│ - Venta pasa a estado: "validada"   │
│ - Vendedor recibe email              │
│ - Comprador recibe email             │
│ - Log registrado                     │
└──────────────────────────────────────┘
```

---

## 📊 FLUJO DE DASHBOARD (SIN SQL DIRECTO)

```
┌────────────────────────────────────────────────┐
│      DASHBOARD ADMINISTRATIVO (ELOQUENT)       │
└────────────────────────────────────────────────┘

Consulta 1: Total de Usuarios
┌──────────────────────────────────┐
│ $totalUsuarios = Usuario::count()│
│ Resultado: 100                   │
└──────────────────────────────────┘

Consulta 2: Total de Vendedores
┌──────────────────────────────────────────────┐
│ $totalVendedores =                           │
│   Usuario::where('es_vendedor', true)        │
│          ->count()                           │
│ Resultado: 30                                │
└──────────────────────────────────────────────┘

Consulta 3: Total de Compradores
┌──────────────────────────────────────────────┐
│ $totalCompradores =                          │
│   Usuario::where('es_vendedor', false)       │
│          ->count()                           │
│ Resultado: 70                                │
└──────────────────────────────────────────────┘

Consulta 4: Productos por Categoría
┌──────────────────────────────────────────────┐
│ $categorias =                                │
│   Categoria::with(['productos.ventas.cliente'])
│             ->withCount('productos')         │
│             ->get()                          │
│                                              │
│ Resultado: [                                 │
│   {                                          │
│     id: 1,                                   │
│     nombre: "Electrónica",                   │
│     productos_count: 15,                     │
│     productos: [                             │
│       { id: 1, ..., ventas: [...] }         │
│     ]                                        │
│   },                                         │
│   ...                                        │
│ ]                                            │
└──────────────────────────────────────────────┘

Consulta 5: Producto Más Vendido
┌──────────────────────────────────────────────┐
│ $productoMasVendido =                        │
│   Producto::with('ventas')                   │
│           ->get()                            │
│           ->map(fn($p) =>                    │
│               $p->unidades_vendidas =        │
│                   $p->ventas->sum('cantidad')
│           )                                  │
│           ->sortByDesc('unidades_vendidas')  │
│           ->first()                          │
│                                              │
│ Resultado: {                                 │
│   id: 5,                                     │
│   nombre: "Laptop",                          │
│   unidades_vendidas: 25,                     │
│   ...                                        │
│ }                                            │
└──────────────────────────────────────────────┘

Consulta 6: Comprador Más Frecuente por Categoría
┌──────────────────────────────────────────────┐
│ $compradoresFrecuentesPorCategoria =          │
│   $categorias->map(function($categoria) {    │
│       $ventas = $categoria->productos       │
│                       ->flatMap->ventas;    │
│                                              │
│       $grupoCompradores = $ventas           │
│           ->groupBy('cliente_id')           │
│           ->sortByDesc(fn($v)=>$v->count());
│                                              │
│       $comprador = $grupoCompradores        │
│           ->first()?->first()?->cliente;    │
│                                              │
│       return [                               │
│           'categoria' => $categoria,        │
│           'comprador' => $comprador,        │
│           'compras' => count($ventasCliente)│
│       ];                                     │
│   })                                         │
│                                              │
│ Resultado: [                                 │
│   {                                          │
│     categoria: { id: 1, nombre: "..." },    │
│     comprador: { id: 5, nombre: "Juan" },   │
│     compras: 12                              │
│   },                                         │
│   ...                                        │
│ ]                                            │
└──────────────────────────────────────────────┘

RELACIÓN HASMANYTHROUGH ⭐ OBLIGATORIA
┌──────────────────────────────────────────────┐
│ Usuario::class => CategoriaProducto::class    │
│    through: Producto::class                  │
│                                              │
│ Usuario $usuario                             │
│    ↓ (hasMany)                               │
│ Producto (usuario_id)                        │
│    ↓ (hasMany)                               │
│ CategoriaProducto (producto_id)              │
│                                              │
│ Implementación en Usuario:                   │
│                                              │
│ public function categoriasThroughProductos() │
│ {                                            │
│     return $this->hasManyThrough(            │
│         CategoriaProducto::class,            │
│         Producto::class,                     │
│         'usuario_id',      // FK en Producto │
│         'producto_id',     // FK en CatProd  │
│         'id',              // Key en Usuario │
│         'id'               // Key en Producto│
│     );                                       │
│ }                                            │
│                                              │
│ Uso:                                         │
│ $usuario->categoriasThroughProductos()->get()│
│ → Retorna todos los CategoriaProducto        │
│   a través de sus productos                  │
└──────────────────────────────────────────────┘
```

---

## 🌱 FLUJO DE SEEDERS

```
┌────────────────────────────────────────────┐
│         POBLAMIENTO DE BASE DE DATOS        │
└────────────────────────────────────────────┘

php artisan db:seed
    ↓
DatabaseSeeder::run()
    ├─► UsuarioSeeder::run()
    │   ├─ 3 usuarios manuales (roles específicos)
    │   ├─ 28 usuarios factory: cliente + vendedor
    │   └─ 69 usuarios factory: cliente
    │   Total: 100 usuarios (30 vendedores, 70 compradores)
    │
    ├─► CategoriaSeeder::run()
    │   └─ Crea N categorías
    │
    ├─► ProductoSeeder::run()
    │   ├─ foreach ($vendedores) { // 30 vendedores
    │   │   foreach (range(1, 3)) { // 3 productos each
    │   │       ├─ Crea producto con SVG foto
    │   │       └─ Asigna 1-3 categorías
    │   │   }
    │   │ }
    │   Total: 90+ productos
    │
    └─► VentaSeeder::run()
        └─ Crea ventas de prueba
           (cliente-vendedor)

RESULTADOS ESPERADOS:
├─ Usuarios: 100
│  ├─ Vendedores: 30
│  ├─ Compradores: 70
│  ├─ Admin: 1
│  └─ Gerente: 1
│
├─ Productos: 90+
│  ├─ Cada uno tiene: vendor_id
│  ├─ Cada uno tiene: fotos (JSON array)
│  ├─ Cada uno está en: 1-3 categorías
│  └─ Almacenadas en: storage/app/public/productos/
│
├─ Categorías: N
│  └─ Cada una tiene: M productos
│
├─ Relaciones:
│  ├─ Usuario.productos (hasMany)
│  ├─ Usuario.ventas (hasMany)
│  ├─ Producto.categorias (belongsToMany)
│  └─ Usuario.categoriasThroughProductos (hasManyThrough)
│
└─ Archivos:
   └─ storage/app/public/productos/*.svg
```

---

## 🛣️ FLUJO DE RUTAS

```
┌────────────────────────────────────────────────────┐
│              RUTAS Y MIDDLEWARE                     │
└────────────────────────────────────────────────────┘

PÚBLICAS (Sin middleware)
│
├─ GET  /                          → HomeController@index
├─ GET  /about                     → HomeController@about
├─ GET  /contact                   → HomeController@contact
│
├─ GET  /productos                 → ProductoController@index
├─ GET  /productos/{id}            → ProductoController@show
│
├─ GET  /categorias                → CategoriaController@index
├─ GET  /categorias/{id}           → CategoriaController@show
│
└─ POST /logout                    → LoginController@logout (auth)

AUTENTICACIÓN (middleware: guest)
│
├─ GET  /login                     → LoginController@showLoginForm
├─ POST /login                     → LoginController@login
│                                     (validate credentials)
│                                     (send 2FA code)
│
├─ GET  /verificacion-2fa          → LoginController@showTwoFactorForm
├─ POST /verificacion-2fa          → LoginController@verifyTwoFactorCode
│                                     (validate 2FA code)
│                                     (create session)
│
├─ POST /verificacion-2fa/reenviar → LoginController@resendTwoFactorCode
│                                     (generate new code)
│                                     (resend email)
│
├─ GET  /register                  → RegisterController@showRegisterForm
└─ POST /register                  → RegisterController@register

AUTENTICADAS (middleware: auth)
│
├─ GET  /dashboard                 → DashboardController@index
│                                     (require: admin)
│
├─ GET  /carrito                   → VentaController@cart
├─ POST /carrito                   → VentaController@addToCart
├─ PATCH /carrito/{producto}       → VentaController@updateCart
├─ DELETE /carrito/{producto}      → VentaController@removeFromCart
│
├─ GET  /carrito/checkout          → VentaController@checkout
├─ POST /carrito/checkout          → VentaController@processCheckout
│
├─ GET  /mis-compras               → VentaController@index
│
├─ RESOURCE /usuarios              → UsuarioController (authorize)
│  ├─ GET    /usuarios             (policy: viewAny)
│  ├─ GET    /usuarios/create      (policy: create)
│  ├─ POST   /usuarios             (policy: create)
│  ├─ GET    /usuarios/{id}        (policy: view)
│  ├─ GET    /usuarios/{id}/edit   (policy: update)
│  ├─ PUT    /usuarios/{id}        (policy: update)
│  └─ DELETE /usuarios/{id}        (policy: delete)
│
├─ RESOURCE /categorias (except show, index)
│  ├─ GET    /categorias/create
│  ├─ POST   /categorias
│  ├─ GET    /categorias/{id}/edit
│  ├─ PUT    /categorias/{id}
│  └─ DELETE /categorias/{id}
│
├─ RESOURCE /productos (except show, index)
│  ├─ GET    /productos/create
│  ├─ POST   /productos
│  ├─ GET    /productos/{id}/edit
│  ├─ PUT    /productos/{id}
│  └─ DELETE /productos/{id}
│
├─ GET    /ventas/{venta}/ticket   → VentaController@showTicket
│  │                                  (policy: viewTicket)
│  │                                  (serve from private disk)
│  │
│  ├─ POST    /ventas/{venta}/validar → VentaController@validateSale
│  │                                     (policy: validate)
│  │                                     (send emails)
│  │
│  └─ RESOURCE /ventas
│     ├─ GET    /ventas            (policy: viewAny)
│     ├─ GET    /ventas/create     (policy: create)
│     ├─ POST   /ventas            (policy: create)
│     ├─ GET    /ventas/{id}       (policy: view)
│     ├─ GET    /ventas/{id}/edit  (policy: update)
│     ├─ PUT    /ventas/{id}       (policy: update)
│     └─ DELETE /ventas/{id}       (policy: delete)
│
└─ GET  /test-auth                 → Echo auth user (testing)

POLICY AUTHORIZATION
├─ ProductoController::create()    ─► ProductoPolicy::create()
├─ ProductoController::edit()      ─► ProductoPolicy::update()
├─ CategoriaController::create()   ─► CategoriaPolicy::create()
├─ VentaController::show()         ─► VentaPolicy::view()
├─ VentaController::validateSale() ─► VentaPolicy::validate()
├─ VentaController::showTicket()   ─► VentaPolicy::viewTicket()
└─ UsuarioController::*            ─► UsuarioPolicy::*()
```

---

## 🎓 RESUMEN VISUAL

```
┌─────────────────────────────────────────────────────────┐
│          ARQUITECTURA COMPLETA DEL PROYECTO             │
└─────────────────────────────────────────────────────────┘

       ┌───────────────────────────────────────┐
       │         USUARIO (LOGIN 2FA)            │
       │  ✓ Flujo 3-fases                      │
       │  ✓ Código 6 dígitos (5 min)           │
       │  ✓ Email + Logs                       │
       └────────────────┬──────────────────────┘
                        │ Auth
                        ▼
       ┌─────────────────────────────────────┐
       │      RUTAS AUTENTICADAS             │
       │  ✓ Middleware: auth                 │
       │  ✓ Resource routes                  │
       │  └─ CRUD: Usuarios, Productos,      │
       │           Categorías, Ventas        │
       └────────┬───────────────┬────────────┘
                │               │
                ▼               ▼
    ┌─────────────────┐  ┌──────────────────┐
    │   POLICIES      │  │    CONTROLLERS   │
    │  ✓ VentaPolicy  │  │  ✓ ProductoCtrl │
    │  ✓ ProdPolicy   │  │  ✓ VentaCtrl    │
    │  ✓ CatPolicy    │  │  ✓ CatCtrl      │
    │  ✓ UserPolicy   │  │  ✓ DashCtrl     │
    └────────┬────────┘  └────────┬─────────┘
             │                    │
             └────────┬───────────┘
                      ▼
         ┌────────────────────────┐
         │   FORM REQUESTS        │
         │  ✓ 15+ validaciones    │
         │  ✓ required, image,    │
         │    exists, numeric...  │
         └────────┬───────────────┘
                  │
    ┌─────────────┴──────────────┬────────────┐
    ▼                            ▼            ▼
┌──────────┐           ┌──────────────┐  ┌─────────┐
│ MODELS   │           │ RELACIONES   │  │  MAILS  │
│ ✓ Usuario│           │ HasMany      │  │ ✓ Cod2FA│
│ ✓ Venta  │           │ BelongsTo    │  │ ✓ VentaV│
│ ✓ Producto│          │ BelongsToMany│  │ ✓ VentaC│
│ ✓ Categoria│         │ HasManyThrough│ │         │
│ ✓ CodVerif│          │  (OBLIGATORIO)│ └─────────┘
└─────┬────┘           └──────────────┘
      │
      ▼
  ┌─────────────────────────┐
  │   ARCHIVOS (STORAGE)    │
  │ ┌─────────────────────┐ │
  │ │ PÚBLICO (Productos) │ │
  │ │ ✓ Fotos en /storage │ │
  │ │ ✓ URLs públicas     │ │
  │ └─────────────────────┘ │
  │ ┌─────────────────────┐ │
  │ │ PRIVADO (Ventas)    │ │
  │ │ ✓ Tickets autorizados
  │ │ ✓ Policy + Controller
  │ └─────────────────────┘ │
  └─────────────────────────┘

  BASES DE DATOS
  ├─ usuarios (100: 30 vendedores, 70 compradores)
  ├─ productos (90+: 3 por vendedor, fotos JSON)
  ├─ categorias (N: con M productos)
  ├─ categoria_producto (relación many-to-many)
  ├─ ventas (ticket privado, estado pendiente/validada)
  └─ codigos_verificacion (2FA, expiración 5 min)

  LOGS
  ├─ storage/logs/autenticacion.log
  │  └─ 6 eventos: login, código, validación...
  ├─ storage/logs/productos.log
  └─ storage/logs/ventas.log
```

---

✅ **Todos los flujos implementados correctamente según los requisitos**
