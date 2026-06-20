# Shopping Simulator 🛒

Uma plataforma de simulação de compras onde usuários vivenciam a experiência real de compra sem pagar nada efetivamente.

## 🚀 Quick Start

### Pré-requisitos
- Docker & Docker Compose instalados
- Git

### Setup e Execução

```bash
# 1. Clone ou extraia o projeto
cd /Library/WebServer/Documents/shopping-simulator

# 2. Copy environment file
cp .env.example .env

# 3. Inicie os containers
docker-compose up -d

# 4. Aguarde ~2 minutos para Laravel inicializar (migrations, seeders)
# Verifique os logs:
docker-compose logs -f api

# 5. Assim que ver "Laravel development server started", está pronto!
```

## 📋 Acessos

| Serviço | URL | Credenciais |
|---------|-----|-------------|
| **API Laravel** | http://localhost:8000 | N/A |
| **Frontend Vue 3** | http://localhost:3000 | N/A |
| **Grafana** | http://localhost:3001 | admin / admin |
| **Prometheus** | http://localhost:9090 | N/A |
| **PostgreSQL** | localhost:5432 | shopping / shopping_password |
| **Redis** | localhost:6379 | N/A |

## 📊 Observabilidade

### Prometheus
- **Endpoint de métricas**: http://localhost:8000/api/metrics
- **Dashboard**: http://localhost:9090

### Grafana
- **Dashboards automáticos** para:
  - Laravel (requisições, erros, tempo de resposta)
  - PostgreSQL (queries, conexões)
  - Redis (memória, comandos)
  - Sistema (CPU, memória)

## 🛠️ Comandos úteis

```bash
# Ver logs
docker-compose logs -f api          # Laravel
docker-compose logs -f frontend     # Vue 3
docker-compose logs -f prometheus   # Prometheus

# Executar artisan commands
docker-compose exec api php artisan migrate
docker-compose exec api php artisan tinker

# Parar containers
docker-compose down

# Parar e limpar volumes (apaga dados)
docker-compose down -v

# Rebuild após mudanças
docker-compose up -d --build
```

## 📁 Estrutura

```
shopping-simulator/
├── app/                    # Código Laravel
├── routes/                # Rotas (API)
├── database/              # Migrations, seeders, factories
├── resources/             # Views Vue 3
├── public/                # Assets estáticos
├── docker-compose.yml     # Orquestração Docker
├── Dockerfile            # Build da aplicação
├── prometheus.yml        # Config do Prometheus
├── grafana/              # Provisioning do Grafana
└── package.json          # Dependências Node
```

## 🎯 MVP Features

- ✅ Catálogo de produtos
- ✅ Carrinho de compras
- ✅ Checkout simulado
- ✅ Histórico de pedidos
- ✅ Sistema de usuários (autenticação)
- ✅ Moeda virtual
- ✅ Badges/Achievements
- ✅ Observabilidade com Prometheus + Grafana

## 📝 Próximos passos

1. Implementar models e migrations
2. Criar rotas API REST
3. Desenvolver componentes Vue 3
4. Adicionar gamificação
5. Customizar dashboards Grafana

## 🤝 Contribuição

This is a learning project. Feel free to experiment!

## 📄 License

MIT
