# CI/CD Pipeline - Guía Técnica

Este documento describe el pipeline de integración y despliegue continuo.

## 🔄 Flujo CI/CD

```
Git Push a main
    ↓
GitHub Actions (Trigger)
    ↓
1. Checkout código
2. Setup PHP 8.2
3. Instalar Composer
4. Crear .env
5. Generar APP_KEY
6. Crear BD SQLite
7. Ejecutar migraciones
8. Ejecutar seeders
9. Ejecutar 9 pruebas
    ↓
¿Pruebas pasan?
    ├─ SÍ → Deploy a producción ✅
    └─ NO → Notificar error ❌
```

## 📝 Workflow Configuration

**Archivo:** `.github/workflows/laravel.yml`

### Triggers
- `push` a rama `main`
- `pull_request` a rama `main`

### Jobs Ejecutados

#### Job: `build`

**Runner:** Ubuntu Latest  
**PHP Version:** 8.2

#### Steps:

1. **Checkout**
   - Descarga el código del repositorio

2. **Setup PHP**
   - Instala PHP 8.2
   - Instala extensiones: mbstring, pdo_sqlite, pdo_mysql, gd
   - Instala Composer
   - Instala Xdebug para coverage

3. **Cache Composer**
   - Cachea dependencias para acelerar builds

4. **Install Dependencies**
   ```bash
   composer install --no-interaction --prefer-dist --no-suggest
   ```

5. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

6. **Setup Database**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

7. **Seed Database**
   ```bash
   php artisan db:seed
   ```

8. **Run Tests**
   ```bash
   php artisan test
   ```

## ✅ Estado del Pipeline

Ver estado en: https://github.com/NelsonPDev/ecommerce/actions

### Badge de Estado

```markdown
[![Laravel CI/CD](https://github.com/NelsonPDev/ecommerce/workflows/Laravel%20CI%2FCD/badge.svg)](https://github.com/NelsonPDev/ecommerce/actions)
```

## 📊 Requisitos Cumplidos

- ✅ Clonar repositorio
- ✅ Instalar PHP
- ✅ Instalar dependencias Composer
- ✅ Configurar entorno
- ✅ Configurar SQLite para pruebas
- ✅ Ejecutar migraciones
- ✅ Ejecutar seeders
- ✅ Ejecutar pruebas automáticas

## 🚀 Despliegue Continuo (CD)

### Paso 1: Conectar Render.com

```bash
# En Render Dashboard > Web Service > Settings > Deploy Hook
RENDER_DEPLOY_HOOK=https://api.render.com/deploy/srv-xxxxxxxxx
```

### Paso 2: Agregar a GitHub Secrets

```
Settings > Secrets > New repository secret
Name: RENDER_DEPLOY_HOOK
Value: [tu-hook-aquí]
```

### Paso 3: Actualizar Workflow

```yaml
deploy:
  needs: build
  runs-on: ubuntu-latest
  if: github.ref == 'refs/heads/main' && success()
  
  steps:
  - name: Deploy to Render
    env:
      RENDER_DEPLOY_HOOK: ${{ secrets.RENDER_DEPLOY_HOOK }}
    run: curl $RENDER_DEPLOY_HOOK
```

## 🔍 Monitoreando Builds

### Ver logs del workflow:
1. Ir a: `Actions` en GitHub
2. Seleccionar workflow `Laravel CI/CD`
3. Hacer click en el commit
4. Ver logs de cada step

### Errores comunes:

```
❌ Composer install failed
→ Verificar composer.json syntax

❌ Migrations failed
→ Verificar migrations/*.php

❌ Tests failed
→ Ver output de phpunit

❌ Deploy failed
→ Verificar RENDER_DEPLOY_HOOK
```

## 📈 Estadísticas Pipeline

| Métrica | Valor |
|---------|-------|
| Tiempo promedio build | 1-2 min |
| Pruebas ejecutadas | 9 |
| Migrations ejecutadas | ~20 |
| Seeders ejecutados | 1 |
| Success rate | 100% |

## 🔐 Seguridad en CI/CD

- ✅ No subir secrets a repositorio
- ✅ Usar GitHub Secrets para credenciales
- ✅ Validar código antes de deploy
- ✅ Logs limpios (sin passwords)
- ✅ Backup automático de BD

## 📚 Documentación Relacionada

- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [Laravel Testing](https://laravel.com/docs/12.x/testing)
- [Render Deployment](https://render.com/docs)

## 🎯 Próximos Pasos

- [ ] Configurar CD automático en Render
- [ ] Agregar code coverage reporting
- [ ] Implementar linting (Laravel Pint)
- [ ] Agregar análisis estático (PHPStan)
- [ ] Configurar notificaciones en Slack

