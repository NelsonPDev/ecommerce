# Configuración de Despliegue

Este documento explica cómo desplegar la aplicación en producción.

## 🚀 Despliegue en Render.com (Recomendado)

### Paso 1: Preparar Variables de Entorno

1. Copiar `.env.production.example` a `.env.production`
2. Actualizar valores con credenciales reales
3. **NUNCA subir `.env` o `.env.production` al repositorio**

### Paso 2: Crear Servicio en Render

```
1. Ir a https://render.com y crear cuenta
2. Click en "New +" > "Web Service"
3. Conectar repositorio GitHub
4. Seleccionar rama "main"
5. Configurar:
   - Name: ecommerce
   - Runtime: Docker
   - Build: composer install && npm install && npm run build
   - Start: php artisan serve --host 0.0.0.0 --port $PORT
```

### Paso 3: Configurar Variables de Entorno en Render

En Render Dashboard > Settings > Environment:

```
APP_NAME=E-Commerce
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ecommerce-xxxx.onrender.com

DB_CONNECTION=mysql
DB_HOST=mysql-host.render.com
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=usuario
DB_PASSWORD=contraseña_fuerte

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=contraseña_app_gmail
```

### Paso 4: Deploy Automático

Render se integra con GitHub Actions automáticamente:
- Cada push a `main` dispara el workflow
- Si las pruebas pasan, se despliega automáticamente
- Ver logs en Render Dashboard

## 🔒 Seguridad en Producción

✅ **Hacer:**
- Usar variables de entorno para credenciales
- Generar APP_KEY único por ambiente
- Usar HTTPS (certificado SSL gratuito en Render)
- Configurar backups automáticos
- Monitorear logs de error

❌ **NO Hacer:**
- Subir `.env` a Git
- Usar APP_DEBUG=true en producción
- Hardcodear credenciales
- Usar password por defecto en BD

## 📊 Monitorear Producción

```bash
# Ver logs en vivo
php artisan tail

# Verificar estado de la aplicación
curl https://tu-url.com/health

# Ejecutar command en producción
php artisan command:name
```

## 🔄 Rollback Rápido

Si algo sale mal en producción:

```bash
# En Render Dashboard:
1. Settings > Deployments
2. Seleccionar deploy anterior
3. Click "Redeploy"
```

## 📞 Soporte

- Logs de error: `storage/logs/laravel.log`
- Errores de BD: Revisar credenciales en Render
- Errores de correo: Verificar MAIL_* en variables

