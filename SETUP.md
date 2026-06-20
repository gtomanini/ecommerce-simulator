# 🚀 Setup Guide - Shopping Simulator

## Pré-requisitos

Certifique-se de ter instalado:
- **Docker Desktop** (https://www.docker.com/products/docker-desktop)
- **Docker Compose** (incluído no Docker Desktop)

## Passo a Passo

### 1️⃣ Navegue até o diretório do projeto

```bash
cd /Library/WebServer/Documents/shopping-simulator
```

### 2️⃣ Inicie os containers

```bash
docker-compose up -d
```

Isso vai:
- ✅ Criar e iniciar todos os containers
- ✅ Instalar Laravel 11 automaticamente (primeira vez ~2-3 min)
- ✅ Instalar dependências PHP e Node
- ✅ Rodar migrations e seeders
- ✅ Iniciar todos os serviços

### 3️⃣ Aguarde o Laravel inicializar

```bash
# Ver logs da API
docker-compose logs -f api
```

**Aguarde até ver:**
```
Laravel development server started: http://0.0.0.0:8000
```

Isso significa que o Laravel está pronto! Pode pressionar `Ctrl+C` para sair.

### 4️⃣ Acesse os serviços

| Serviço | URL | Credenciais |
|---------|-----|-------------|
| **Frontend** | http://localhost:3000 | - |
| **API** | http://localhost:8000 | - |
| **Metrics API** | http://localhost:8000/api/metrics | - |
| **Grafana** | http://localhost:3001 | admin / admin |
| **Prometheus** | http://localhost:9090 | - |

## 🎮 Primeiros Testes

### Testar a API

```bash
# Health check
curl http://localhost:8000/api/health

# Ver métricas do Prometheus
curl http://localhost:8000/api/metrics
```

### Acessar Grafana

1. Acesse http://localhost:3001
2. Login: `admin` / `admin`
3. Veja os dashboards automáticos

## 📋 Comandos Úteis

### Ver logs

```bash
# Ver todos os logs
docker-compose logs -f

# Apenas Laravel
docker-compose logs -f api

# Apenas Frontend
docker-compose logs -f frontend

# Apenas Prometheus
docker-compose logs -f prometheus
```

### Executar comandos Laravel

```bash
# Entrar no container
docker-compose exec api bash

# Rodar Artisan commands
docker-compose exec api php artisan tinker
docker-compose exec api php artisan migrate
docker-compose exec api php artisan db:seed
```

### Executar comandos Node/NPM

```bash
# Instalar novo pacote
docker-compose exec frontend npm install axios

# Build para produção
docker-compose exec frontend npm run build
```

### Parar tudo

```bash
# Parar mas manter dados
docker-compose stop

# Parar e deletar containers (mantém volumes)
docker-compose down

# Parar, deletar containers E deletar dados
docker-compose down -v
```

### Rebuild após mudanças

```bash
# Rebuild da imagem Docker
docker-compose up -d --build
```

## 🐛 Troubleshooting

### "Port already in use"

Se alguma porta estiver em uso, você pode mudá-la no `docker-compose.yml`:

```yaml
ports:
  - "8001:8000"  # Muda para port 8001
```

### Laravel não inicia

Verifique os logs:
```bash
docker-compose logs api
```

### PostgreSQL não conecta

Aguarde alguns segundos. Você pode ver o status de health:
```bash
docker-compose ps
```

Deve mostrar `postgres` com status `healthy`.

### Limpar tudo e recomeçar

```bash
# Remove containers, networks, volumes
docker-compose down -v

# Recria tudo do zero
docker-compose up -d
```

## 📚 Próximos Passos

1. Edite `/laravel/app/Models` para criar seus models
2. Crie migrations em `/laravel/database/migrations`
3. Desenvolva componentes Vue em `/resources/js`
4. Configure rotas em `/laravel/routes/api.php`
5. Customize dashboards no Grafana

## 💡 Dicas

- **HMR (Hot Module Replacement)**: Vue vai auto-reload em http://localhost:3000
- **Laravel Tinker**: Use para explorar sua aplicação
- **Grafana**: Crie dashboards customizados para suas métricas
- **PostgreSQL**: Pode conectar com tools como DBeaver usando `localhost:5432`

## ✅ Verificar se tudo funcionou

Todos esses comandos devem rodar sem erro:

```bash
# Laravel rodando
curl http://localhost:8000

# Frontend rodando
curl http://localhost:3000

# API respondendo
curl http://localhost:8000/api/metrics

# Prometheus scraping
curl http://localhost:9090/api/v1/targets
```

---

🎉 **Se conseguiu chegar aqui, parabéns! Seu ambiente está pronto!**

Next: comece a desenvolver seus modelos, rotas e componentes!
