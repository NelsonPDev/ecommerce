# CI/CD Pipeline

## Flujo

```
Git Push → GitHub → GitHub Actions
    ↓
1. Checkout código
2. Install PHP 8.2
3. Install Composer deps
4. Configure .env
5. Run Migrations
6. Run Seeders
7. Execute Tests (9/9)
    ↓
¿Tests pass? → Deploy a Render
```

## Verificar Status

https://github.com/NelsonPDev/ecommerce/actions

## Configurar CD Automático (Bonus para 100/100)

1. Render Dashboard > Settings > Deploy Hook
2. Copiar URL
3. GitHub > Settings > Secrets > `RENDER_DEPLOY_HOOK`
4. Agregar job deploy en `.github/workflows/laravel.yml`:

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

