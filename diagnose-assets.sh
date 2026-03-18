#!/bin/bash
# Script de diagnóstico para problemas de assets no Jenkins

echo "=========================================="
echo " DIAGNÓSTICO DE ASSETS - JENKINS DEPLOY"
echo "=========================================="
echo ""

# 1. Verificar se Docker está rodando
echo "1️Verificando Docker..."
if docker ps &>/dev/null; then
    echo "Docker disponível"
else
    echo "Docker não respondendo"
    exit 1
fi
echo ""

# 2. Verificar se containers estão up
echo "2️Verificando containers..."
if docker compose -p laravel_docker ps | grep -q "app"; then
    echo "Container app está rodando"
else
    echo "Container app não está rodando"
    echo "Iniciando containers..."
    docker compose -p laravel_docker up -d --build
    sleep 10
fi
echo ""

# 3. Verificar Node/npm versions
echo "3️Verificando Node/npm..."
NODE_VERSION=$(docker compose -p laravel_docker exec -T app node -v 2>/dev/null)
NPM_VERSION=$(docker compose -p laravel_docker exec -T app npm -v 2>/dev/null)
echo "Node: $NODE_VERSION"
echo "npm: $NPM_VERSION"
echo ""

# 4. Verificar package.json e node_modules
echo "4️Verificando dependências..."
if docker compose -p laravel_docker exec -T app test -f package.json; then
    echo "package.json existe"
else
    echo "package.json não encontrado"
    exit 1
fi

if docker compose -p laravel_docker exec -T app test -d node_modules; then
    echo "node_modules existe"
    MODULES_COUNT=$(docker compose -p laravel_docker exec -T app find node_modules -maxdepth 1 | wc -l)
    echo "   Modules: ~$MODULES_COUNT items"
else
    echo "node_modules não existe, rodando npm install..."
    docker compose -p laravel_docker exec -T app npm install --verbose
fi
echo ""

# 5. Tentar build
echo "5️Tentando build dos assets..."
echo "   Rodando: npm run build..."
if docker compose -p laravel_docker exec -T app npm run build; then
    echo "npm run build sucesso!"
else
    echo "npm run build falhou!"
    echo ""
    echo "Mostrando últimas linhas de erro:"
    docker compose -p laravel_docker logs app | tail -20
    exit 1
fi
echo ""

# 6. Verificar se public/build foi criado
echo "6️Verificando public/build..."
if docker compose -p laravel_docker exec -T app test -d public/build; then
    echo "Pasta public/build existe"
    FILES=$(docker compose -p laravel_docker exec -T app find public/build -type f | wc -l)
    echo "   Arquivos: $FILES"
else
    echo "Pasta public/build não foi criada!"
    exit 1
fi
echo ""

# 7. Verificar manifest.json
echo "7️Verificando manifest.json..."
if docker compose -p laravel_docker exec -T app test -f public/build/manifest.json; then
    echo "manifest.json existe"
    echo ""
    echo "Conteúdo:"
    docker compose -p laravel_docker exec -T app head -50 public/build/manifest.json
else
    echo "manifest.json não foi gerado!"
    echo ""
    echo "Listando conteúdo de public/build:"
    docker compose -p laravel_docker exec -T app ls -la public/build/
    exit 1
fi
echo ""

# 8. Listar assets
echo "8️Assets gerados:"
docker compose -p laravel_docker exec -T app find public/build -type f -name "*.js" -o -name "*.css" | sort
echo ""
