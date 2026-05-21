# Despliegue en Render.com

## Paso 1: Crear Cuenta y Conectar GitHub

1. Ir a https://render.com
2. Sign up con GitHub
3. Autorizar repositorio

## Paso 2: Crear Web Service

1. Click "New +" > "Web Service"
2. Seleccionar `ecommerce` repo
3. Rama: `main`
4. Configurar:
   - **Build:** `composer install && npm install && npm run build`
   - **Start:** `php artisan serve --host 0.0.0.0 --port $PORT`

## Paso 3: Variables de Entorno

En Settings > Environment, agregar:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=[tu-db-host]
DB_DATABASE=ecommerce
DB_USERNAME=[usuario]
DB_PASSWORD=[contraseña]

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=[tu-email]
MAIL_PASSWORD=[contraseña-app]
```

## Paso 4: Deploy

Click "Deploy" y esperar a que termine.

## Ver Logs

Render Dashboard > Logs (en vivo)

