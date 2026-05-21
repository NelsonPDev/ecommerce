# Guía de Pruebas Automáticas

Este documento explica las pruebas automáticas implementadas en el proyecto.

## 📊 Resumen de Pruebas

**Total de pruebas:** 9  
**Estado:** ✅ Todas pasando  
**Cobertura:** Funcionalidad crítica

## 🧪 Tipos de Pruebas

### 1. Unit Tests (Unitarias)

Prueban componentes individuales en aislamiento.

```bash
php artisan test tests/Unit
```

**Pruebas:**
- `ExampleTest::that_true_is_true` - Validación básica del framework

### 2. Feature Tests (Funcionales)

Prueban flujos completos de la aplicación.

```bash
php artisan test tests/Feature
```

## 📋 Pruebas Detalladas

### ✅ Autenticación 2FA

**Archivo:** `tests/Feature/TwoFactorAuthenticationTest.php`

#### Test 1: Login requiere código 2FA válido
```php
test_user_must_validate_two_factor_code_before_login()
```
- Usuario intenta login
- Sistema envía código por correo
- Usuario debe ingresar código válido
- ✅ Valida: Redirección, envío de email, base de datos

#### Test 2: Código 2FA inválido rechaza login
```php
test_invalid_two_factor_code_does_not_authenticate_user()
```
- Usuario intenta login
- Ingresa código incorrecto
- ✅ Valida: Sesión con errores, usuario no autenticado

#### Test 3: Código 2FA expirado rechaza login
```php
test_expired_two_factor_code_does_not_authenticate_user()
```
- Código se expira por tiempo
- Intento de login falla
- ✅ Valida: Manejo de tiempos, limpieza de códigos expirados

### ✅ Autorización y Control de Acceso

**Archivo:** `tests/Feature/VentaValidationAndTicketAccessTest.php`

#### Test 4: Solo comprador o manager ven ticket
```php
test_only_buyer_or_manager_can_view_private_ticket()
```
- Crea venta con comprador, vendedor, manager e intruso
- Intenta acceso desde diferentes roles
- ✅ Valida: Políticas de acceso, forbidden (403)

#### Test 5: Manager valida venta y envía notificaciones
```php
test_manager_can_validate_sale_and_send_notifications()
```
- Manager valida una venta pendiente
- Sistema envía emails a comprador y vendedor
- ✅ Valida: Estado de BD, envío de correos, sesión success

### ✅ Dashboard y Estadísticas

**Archivo:** `tests/Feature/DashboardStatisticsAuthorizationTest.php`

#### Test 6: Solo admin recibe datos estadísticos
```php
test_only_administrator_receives_statistics_data_in_dashboard_view()
```
- Admin accede al dashboard
- Cliente accede al dashboard
- ✅ Valida: Datos diferentes según rol, autorización en vista

### ✅ Página Principal

**Archivo:** `tests/Feature/ExampleTest.php`

#### Test 7: Página inicio responde correctamente
```php
test_the_application_returns_a_successful_response()
```
- GET a ruta "/"
- ✅ Valida: Status 200, página cargable

### ✅ Base de Datos y Seeders

**Archivo:** `tests/Feature/DatabaseSeederRequirementsTest.php`

#### Test 8: Seeder crea datos correctos
```php
test_database_seeder_matches_required_distribution_and_relationships()
```
- Ejecuta seeder completo
- Verifica cantidades de usuarios
- ✅ Valida:
  - Total 103 usuarios
  - 30 vendedores
  - 73 clientes normales
  - Todos los vendedores tienen ≥3 productos
  - Todos los productos tienen ≥1 categoría

## 🚀 Ejecución de Pruebas

### Todas las pruebas
```bash
php artisan test
```

### Pruebas con salida detallada
```bash
php artisan test --verbose
```

### Prueba específica
```bash
php artisan test tests/Feature/TwoFactorAuthenticationTest.php
```

### Método específico
```bash
php artisan test tests/Feature/TwoFactorAuthenticationTest.php --filter test_user_must_validate_two_factor_code_before_login
```

### Con coverage (requiere Xdebug)
```bash
php artisan test --coverage
```

## 📝 Estructura de una Prueba

```php
<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiPruebaTest extends TestCase
{
    use RefreshDatabase; // Limpia BD después de cada test
    
    public function test_descripcion_clara_de_que_valida(): void
    {
        // ARRANGE: Preparar datos
        $usuario = Usuario::factory()->create();
        
        // ACT: Ejecutar acción
        $response = $this->actingAs($usuario)->get('/dashboard');
        
        // ASSERT: Validar resultado
        $response->assertStatus(200);
        $this->assertDatabaseHas('usuarios', ['id' => $usuario->id]);
    }
}
```

## ✅ Checklist para Nuevas Pruebas

- [ ] Nombre descriptivo: `test_describe_what_it_validates`
- [ ] Usa `RefreshDatabase` si modifica BD
- [ ] Patrón AAA: Arrange, Act, Assert
- [ ] Valida comportamiento real, no solo status 200
- [ ] Utiliza factories para datos de prueba
- [ ] No deja datos en producción
- [ ] Es independiente de otras pruebas

## 🔍 Validaciones Comunes

```php
// Status HTTP
$response->assertStatus(200);
$response->assertRedirect('/dashboard');
$response->assertNotFound(); // 404

// Base de datos
$this->assertDatabaseHas('usuarios', ['correo' => 'test@test.com']);
$this->assertDatabaseCount('usuarios', 5);
$this->assertDatabaseMissing('usuarios', ['correo' => 'ghost@test.com']);

// Sesión y errores
$response->assertSessionHas('success');
$response->assertSessionHasErrors('email');

// Autenticación
$this->assertGuest();
$this->assertAuthenticatedAs($usuario);

// Vista y contenido
$response->assertViewHas('usuarios', $usuarios);
$response->assertSee('Bienvenido');

// Emails (con Mail::fake())
Mail::assertSent(MailClass::class);
Mail::assertNotSent(MailClass::class);
```

## 📊 Métricas de Prueba

| Métrica | Valor |
|---------|-------|
| Pruebas Totales | 9 |
| Pruebas Exitosas | 9 |
| Tasa de Éxito | 100% |
| Assertions Totales | 44 |
| Tiempo Ejecución | ~2.5s |

## 🔧 Troubleshooting

### "Test DB already exists"
```bash
# Limpiar BD de prueba
php artisan migrate:fresh --env=testing
```

### "Mail not sending in tests"
```php
// Agregar al test
use Illuminate\Support\Facades\Mail;

Mail::fake(); // Antes de enviar correos
```

### "Timeout en prueba"
```bash
# Ejecutar con timeout mayor
php artisan test --timeout=300
```

## 📚 Recursos

- [Laravel Testing](https://laravel.com/docs/12.x/testing)
- [PHPUnit Documentation](https://phpunit.de/)
- [Factories](https://laravel.com/docs/12.x/eloquent-factories)

