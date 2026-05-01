# 🗂️ UBICACIÓN DE ARCHIVOS - Mini Proyecto 3

> Guía de referencia rápida para ubicar cada requisito implementado en el código fuente

---

## 🔐 AUTENTICACIÓN 2FA

| Componente | Ubicación | Archivo | Líneas Clave |
|---|---|---|---|
| **Controlador** | `app/Http/Controllers/` | `LoginController.php` | Login (línea 28) → 2FA (línea 104) |
| **Service** | `app/Services/` | `TwoFactorCodeService.php` | generateFor() / verify() / clearFor() |
| **Modelo** | `app/Models/` | `CodigoVerificacion.php` | Relación belongsTo Usuario |
| **FormRequest** | `app/Http/Requests/Auth/` | `VerifyTwoFactorCodeRequest.php` | Validación de código |
| **Mailable** | `app/Mail/` | `CodigoVerificacionMail.php` | Envío de código |
| **Migration** | `database/migrations/` | `2026_04_29_120100_create_codigos_verificacion_table.php` | Tabla estructura |
| **Ruta** | `routes/` | `web.php` (líneas 24-26) | `/login`, `/verificacion-2fa`, `/reenviar` |
| **Config Logs** | `config/` | `logging.php` (línea 20-26) | Canal 'autenticacion' |
| **Logs** | `storage/logs/` | `autenticacion.log` | 6 eventos: login, código, validación, expirado, inválido |
| **Vistas** | `resources/views/` | `login.blade.php`, `auth/two-factor.blade.php` | Formularios |
| **Email Vista** | `resources/views/emails/` | `codigo-verificacion.blade.php` | Contenido email |

---

## 📁 GESTIÓN DE ARCHIVOS

| Componente | Ubicación | Archivo | Detalles |
|---|---|---|---|
| **Configuración** | `config/` | `filesystems.php` | Líneas 32-40 (public), 42-49 (private) |
| **Disco Público** | `storage/app/public/` | `productos/*.svg` | SVG generados por ProductoSeeder |
| **Disco Privado** | `storage/app/private/` | `tickets/*` | Guardados por VentaController::storeTicket() |
| **Modelo Producto** | `app/Models/` | `Producto.php` (línea 51-60) | `fotoUrls()`, `primeraFotoUrl()` |
| **Modelo Venta** | `app/Models/` | `Venta.php` (línea 13) | Campo `ticket` (string nullable) |
| **Controller - Guardar** | `app/Http/Controllers/` | `VentaController.php` (línea 411) | `storeTicket()` → Storage::disk('private')->store() |
| **Controller - Servir** | `app/Http/Controllers/` | `VentaController.php` (línea 275) | `showTicket()` → Storage::disk('private')->response() |
| **Migration Fotos** | `database/migrations/` | `2026_04_29_120200_add_fotos_to_productos_table.php` | JSON field |
| **Migration Ticket** | `database/migrations/` | `2026_04_29_120300_add_ticket_and_validation_fields_to_ventas_table.php` | Ticket + Estado |
| **Seeder** | `database/seeders/` | `ProductoSeeder.php` (línea 26) | Creación de SVG en public disk |

---

## 🔒 POLÍTICAS DE ACCESO (POLICIES)

| Policy | Ubicación | Archivo | Métodos |
|---|---|---|---|
| **Venta** | `app/Policies/` | `VentaPolicy.php` | viewAny, view, create, update, delete, validate, **viewTicket** |
| **Producto** | `app/Policies/` | `ProductoPolicy.php` | viewAny, view, create, update, delete, buy |
| **Categoria** | `app/Policies/` | `CategoriaPolicy.php` | viewAny, view, create, update, delete |
| **Usuario** | `app/Policies/` | `UsuarioPolicy.php` | viewAny, view, create, update, delete |
| **Registro** | `app/Providers/` | `AppServiceProvider.php` (línea 18-21) | Gate::policy() para cada modelo |
| **Authorization** | Cada Controller | `ProductoController.php` | `$this->authorize('create', Producto::class)` |
| **Vistas** | `resources/views/` | `*.blade.php` | `@can('update', $venta)` / `@endcan` |

---

## 📧 NOTIFICACIONES (MAILABLES)

| Mailable | Ubicación | Archivo | Disparo | Destinatario |
|---|---|---|---|---|
| **Código 2FA** | `app/Mail/` | `CodigoVerificacionMail.php` | LoginController::login() (línea 50) | Usuario (correo) |
| **Venta Vendedor** | `app/Mail/` | `VentaValidadaVendedorMail.php` | VentaController::validateSale() (línea 330) | Vendedor (correo) |
| **Venta Comprador** | `app/Mail/` | `VentaValidadaCompradorMail.php` | VentaController::validateSale() (línea 331) | Comprador (correo) |
| **Email Código** | `resources/views/emails/` | `codigo-verificacion.blade.php` | Con $usuario, $codigoVerificacion |
| **Email Vendedor** | `resources/views/emails/` | `venta-validada-vendedor.blade.php` | Con $venta (producto, cliente, total) |
| **Email Comprador** | `resources/views/emails/` | `venta-validada-comprador.blade.php` | Con $venta (vendedor email, producto, monto) |

---

## 📊 DASHBOARD ADMINISTRATIVO

| Consulta | Ubicación | Archivo | Línea | Método |
|---|---|---|---|---|
| **Total Usuarios** | `app/Http/Controllers/` | `DashboardController.php` | 42 | `Usuario::count()` |
| **Total Vendedores** | `app/Http/Controllers/` | `DashboardController.php` | 43 | `Usuario::where('es_vendedor', true)->count()` |
| **Total Compradores** | `app/Http/Controllers/` | `DashboardController.php` | 44 | `Usuario::where('es_vendedor', false)->count()` |
| **Productos por Categoría** | `app/Http/Controllers/` | `DashboardController.php` | 46 | `Categoria::with(['productos.ventas.cliente'])->withCount('productos')->get()` |
| **Producto Más Vendido** | `app/Http/Controllers/` | `DashboardController.php` | 48-54 | Map + sortByDesc('unidades_vendidas') |
| **Comprador Frecuente por Cat** | `app/Http/Controllers/` | `DashboardController.php` | 56-70 | Map + groupBy('cliente_id') + first |
| **HasManyThrough** | `app/Models/` | `Usuario.php` (línea 94-104) | Relación | `hasManyThrough(CategoriaProducto, Producto, ...)` |
| **Vista Dashboard** | `resources/views/` | `dashboard.blade.php` | Compacto | Muestra todas las consultas |

---

## 🌱 POBLAMIENTO DE DATOS

| Seeder | Ubicación | Archivo | Usuarios Creados | Detalles |
|---|---|---|---|---|
| **Base** | `database/seeders/` | `DatabaseSeeder.php` | - | Llama a todos los seeders |
| **Usuarios** | `database/seeders/` | `UsuarioSeeder.php` | **100 total** | 3 manuales + 28 factory + 69 factory |
| **Usuarios Manual** | `database/seeders/` | `UsuarioSeeder.php` (línea 8-27) | 3 | Juan (cliente), Maria (gerente), Pedro (admin) |
| **Usuarios Factory** | `database/seeders/` | `UsuarioSeeder.php` (línea 29-30) | 97 | 28 cliente+vendedor, 69 solo cliente |
| **Distribución** | - | - | **30 vendedores** / **70 compradores** | Cumple requisito |
| **Factory** | `database/factories/` | `UsuarioFactory.php` | - | Métodos: cliente(), vendedor(), administrador() |
| **Productos** | `database/seeders/` | `ProductoSeeder.php` | **90+** | 30 vendedores × 3 productos |
| **Fotos** | `database/seeders/` | `ProductoSeeder.php` (línea 18-26) | 90+ | SVG generados en storage/app/public |
| **Categorías** | `database/seeders/` | `CategoriaSeeder.php` | N | Categorías de prueba |
| **Relaciones** | `database/seeders/` | `ProductoSeeder.php` (línea 28-30) | - | Cada producto 1-3 categorías |
| **Ventas** | `database/seeders/` | `VentaSeeder.php` | - | Ventas de prueba cliente-vendedor |

---

## 📋 CRUD COMPLETO

### Productos
| Componente | Ubicación | Archivo |
|---|---|---|
| **Model** | `app/Models/` | `Producto.php` |
| **Migration** | `database/migrations/` | `2026_04_07_153343_create_productos_table.php` |
| **Controller** | `app/Http/Controllers/` | `ProductoController.php` |
| **FormRequest - Store** | `app/Http/Requests/` | `StoreProductoRequest.php` |
| **FormRequest - Update** | `app/Http/Requests/` | `UpdateProductoRequest.php` |
| **Policy** | `app/Policies/` | `ProductoPolicy.php` |
| **Rutas** | `routes/` | `web.php` (resource, except show/index) |

### Categorías
| Componente | Ubicación | Archivo |
|---|---|---|
| **Model** | `app/Models/` | `Categoria.php` |
| **Migration** | `database/migrations/` | `2026_04_07_153030_create_categorias_table.php` |
| **Controller** | `app/Http/Controllers/` | `CategoriaController.php` |
| **FormRequest - Store** | `app/Http/Requests/` | `StoreCategoriaRequest.php` |
| **FormRequest - Update** | `app/Http/Requests/` | `UpdateCategoriaRequest.php` |
| **Policy** | `app/Policies/` | `CategoriaPolicy.php` |
| **Rutas** | `routes/` | `web.php` (resource, except show/index) |

### Ventas
| Componente | Ubicación | Archivo |
|---|---|---|
| **Model** | `app/Models/` | `Venta.php` |
| **Migration** | `database/migrations/` | `2026_04_07_153344_create_ventas_table.php` |
| **Controller** | `app/Http/Controllers/` | `VentaController.php` |
| **FormRequest - Store** | `app/Http/Requests/` | `StoreVentaRequest.php` |
| **FormRequest - Update** | `app/Http/Requests/` | `UpdateVentaRequest.php` |
| **FormRequest - Validate** | `app/Http/Requests/` | `ValidateVentaRequest.php` |
| **Policy** | `app/Policies/` | `VentaPolicy.php` |
| **Rutas** | `routes/` | `web.php` (resource + custom validateSale, showTicket) |

---

## ✔️ VALIDACIONES (FORM REQUESTS)

| FormRequest | Ubicación | Archivo | Validaciones Clave |
|---|---|---|---|
| **LoginUsuarioRequest** | `app/Http/Requests/Auth/` | `LoginUsuarioRequest.php` | required\|email, required\|string\|min:3\|max:255 |
| **RegisterUsuarioRequest** | `app/Http/Requests/Auth/` | `RegisterUsuarioRequest.php` | required, email, unique, confirmed |
| **VerifyTwoFactorCodeRequest** | `app/Http/Requests/` | `VerifyTwoFactorCodeRequest.php` | Validación de código |
| **StoreProductoRequest** | `app/Http/Requests/` | `StoreProductoRequest.php` | required\|string, image\|max:4096, exists:categorias |
| **UpdateProductoRequest** | `app/Http/Requests/` | `UpdateProductoRequest.php` | Similar a Store |
| **StoreCategoriaRequest** | `app/Http/Requests/` | `StoreCategoriaRequest.php` | required\|string, min:3 |
| **UpdateCategoriaRequest** | `app/Http/Requests/` | `UpdateCategoriaRequest.php` | required\|string, min:3 |
| **StoreVentaRequest** | `app/Http/Requests/` | `StoreVentaRequest.php` | exists:productos, integer\|min:1, image\|max:4096 |
| **UpdateVentaRequest** | `app/Http/Requests/` | `UpdateVentaRequest.php` | integer, exists, numeric\|min:0, nullable\|image |
| **ValidateVentaRequest** | `app/Http/Requests/` | `ValidateVentaRequest.php` | Policy authorization |
| **ProcessCheckoutRequest** | `app/Http/Requests/` | `ProcessCheckoutRequest.php` | Validación de checkout |
| **AddToCartRequest** | `app/Http/Requests/` | `AddToCartRequest.php` | producto_id, cantidad |
| **UpdateCartRequest** | `app/Http/Requests/` | `UpdateCartRequest.php` | cantidad |
| **StoreUsuarioRequest** | `app/Http/Requests/` | `StoreUsuarioRequest.php` | Nombres, correo, clave |
| **UpdateUsuarioRequest** | `app/Http/Requests/` | `UpdateUsuarioRequest.php` | Validaciones de actualización |
| **ProfileUpdateRequest** | `app/Http/Requests/` | `ProfileUpdateRequest.php` | Perfil de usuario |

---

## ⚙️ CONFIGURACIÓN OBLIGATORIA

| Config | Ubicación | Archivo | Línea Clave | Detalle |
|---|---|---|---|---|
| **Rutas** | `routes/` | `web.php` | 1-62 | Login, 2FA, CRUD, Carrito, Dashboard |
| **Discos** | `config/` | `filesystems.php` | 32-49 | public disk (URL pública), private disk (no URL) |
| **Logging** | `config/` | `logging.php` | 20-26 | Canal 'autenticacion' → storage/logs/autenticacion.log |
| **Logging Productos** | `config/` | `logging.php` | 28-33 | Canal 'productos' → storage/logs/productos.log |
| **Logging Ventas** | `config/` | `logging.php` | 35-40 | Canal 'ventas' → storage/logs/ventas.log |
| **Autenticación** | `config/` | `auth.php` | 13-16 | Provider: Usuario::class |
| **Storage Link** | `public/` | `storage/` | Symlink | Acceso público a archivos |

---

## 📝 LOGS

| Log | Ubicación | Ruta Completa | Eventos |
|---|---|---|---|
| **Autenticación** | `storage/logs/` | `autenticacion.log` | Login (fase 1), Código generado, Código validado, Código inválido, Código expirado, Login fallido |
| **Productos** | `storage/logs/` | `productos.log` | CRUD operaciones (crear, actualizar, borrar) |
| **Ventas** | `storage/logs/` | `ventas.log` | Venta creada, Checkout procesado, Venta validada, Errores |

---

## 🧩 MODELOS Y RELACIONES

| Modelo | Ubicación | Archivo | Relaciones Clave |
|---|---|---|---|
| **Usuario** | `app/Models/` | `Usuario.php` | hasMany(Producto), hasMany(CodigoVerificacion), hasMany(Venta as cliente/vendedor), **hasManyThrough(CategoriaProducto)** |
| **Producto** | `app/Models/` | `Producto.php` | belongsTo(Usuario), belongsToMany(Categoria), hasMany(Venta) |
| **Categoria** | `app/Models/` | `Categoria.php` | belongsToMany(Producto) |
| **Venta** | `app/Models/` | `Venta.php` | belongsTo(Producto), belongsTo(Usuario as cliente), belongsTo(Usuario as vendedor), belongsTo(Usuario as validador) |
| **CodigoVerificacion** | `app/Models/` | `CodigoVerificacion.php` | belongsTo(Usuario) |
| **CategoriaProducto** | `app/Models/` | `CategoriaProducto.php` | Modelo intermedio |

---

## 🔐 RESTRICCIONES CUMPLIDAS

| Restricción | Estado | Donde se verifica |
|---|---|---|
| NO Laravel Breeze | ✅ | No hay en composer.json, auth manual |
| NO Laravel Jetstream | ✅ | No hay en composer.json, auth manual |
| NO Laravel Fortify | ✅ | No hay en composer.json, auth manual |
| NO paquetes 2FA | ✅ | TwoFactorCodeService implementada manualmente |
| NO DB::raw() en dashboard | ✅ | DashboardController solo Eloquent |
| NO queries SQL manuales | ✅ | DashboardController relaciones puras |
| Ticket en disco privado | ✅ | storeTicket() → disk('private') |
| Acceso autenticado tickets | ✅ | showTicket() + VentaPolicy::viewTicket() |
| HasManyThrough obligatorio | ✅ | Usuario→categoriasThroughProductos() |

---

## 📚 REFERENCIAS RÁPIDAS

**Verificar 2FA:**
```bash
# Ver logs
tail -f storage/logs/autenticacion.log

# Probar flujo
php artisan tinker
> Usuario::first()->codigosVerificacion
```

**Verificar Archivos:**
```bash
# Ver discos
ls -la storage/app/public/productos/
ls -la storage/app/private/tickets/

# Rutas
php artisan storage:link
```

**Verificar Policies:**
```bash
php artisan tinker
> $venta = Venta::first()
> $user = Usuario::first()
> auth()->user()->can('viewTicket', $venta)
```

**Ver Base de Datos:**
```bash
php artisan tinker
> Usuario::count()  # 100
> Usuario::where('es_vendedor', true)->count()  # 30
> Producto::count()  # 90+
```

---

✅ **Documento actualizado: 30/04/2026**
