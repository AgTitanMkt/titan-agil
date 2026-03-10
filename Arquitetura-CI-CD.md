# 📄 Documentação – CI/CD do Projeto Titan Agil

## Visão Geral

O processo de **CI/CD (Continuous Integration / Continuous Deployment)** do projeto **Titan Agil** utiliza as seguintes tecnologias:

* Jenkins para automação do pipeline
* Docker para containerização da aplicação
* Docker Compose para orquestração dos containers
* Nginx como servidor web
* Laravel como framework backend
* Node.js para build do frontend
* GitHub como repositório de código

O deploy foi projetado utilizando a estratégia **Blue-Green Deployment**, permitindo **deploy sem downtime** e maior segurança em caso de falhas.

---

# Arquitetura do Deploy

O fluxo geral funciona da seguinte forma:

```
Developer
   ↓
Push para GitHub
   ↓
Jenkins Pipeline
   ↓
Build nova imagem Docker
   ↓
Subir novo container (Blue ou Green)
   ↓
Executar migrations
   ↓
Health Check
   ↓
Nginx troca o tráfego para o novo container
   ↓
Container antigo é removido
```

Essa estratégia garante que **a aplicação nunca fique fora do ar durante um deploy**.

---

# Estratégia Blue-Green Deployment

A aplicação utiliza dois ambientes de execução:

```
laravel_blue
laravel_green
```

Apenas **um ambiente fica ativo por vez**.

### Exemplo de funcionamento

Situação inicial:

```
Nginx → laravel_blue
```

Durante o deploy:

```
Jenkins inicia laravel_green
```

Após validação:

```
Nginx → laravel_green
```

Depois disso:

```
laravel_blue é removido
```

Benefícios:

* Deploy sem downtime
* Possibilidade de rollback rápido
* Ambiente novo testado antes de entrar em produção

---

# Estrutura do Projeto

Estrutura relevante para o processo de deploy:

```
project-root
│
├─ docker/
│   │
│   ├─ docker-compose.yml
│   │
│   ├─ php/
│   │   └─ Dockerfile
│   │
│   └─ nginx/
│       └─ default.conf
│
├─ Jenkinsfile
├─ .env.example
└─ Laravel Project
```

---

# Arquitetura de Containers

Arquitetura atual do servidor:

```
Server
│
├─ Jenkins
│
├─ Docker
│   │
│   ├─ laravel_blue
│   ├─ laravel_green
│   └─ laravel_nginx
```

### Containers

| Container     | Função                                           |
| ------------- | ------------------------------------------------ |
| laravel_blue  | Ambiente da aplicação (versão atual ou anterior) |
| laravel_green | Ambiente da nova versão da aplicação             |
| laravel_nginx | Servidor web que direciona o tráfego             |

---

# Jenkins Pipeline

O pipeline é definido através de um **Jenkinsfile** no repositório.

Principais etapas executadas:

1. Limpeza do workspace
2. Clonar repositório
3. Detectar ambiente ativo (Blue ou Green)
4. Gerar arquivo `.env`
5. Build da imagem Docker
6. Subir novo container
7. Executar migrations
8. Otimizar Laravel
9. Health check da aplicação
10. Trocar container ativo no Nginx
11. Remover container antigo

---

# Etapa 1 – Limpeza do Workspace

Antes do build, o Jenkins remove arquivos de builds anteriores:

```
cleanWs()
```

Isso evita conflitos entre versões.

---

# Etapa 2 – Clonar Repositório

O Jenkins clona o código do repositório GitHub:

```
checkout scm
```

O código fica disponível no workspace do Jenkins.

---

# Etapa 3 – Detectar Ambiente Ativo

O pipeline verifica qual container está ativo no momento:

```
laravel_blue
ou
laravel_green
```

Comando utilizado:

```
docker ps --format '{{.Names}}'
```

Se `laravel_blue` estiver ativo:

```
NEW_ENV = green
OLD_ENV = blue
```

Se não:

```
NEW_ENV = blue
OLD_ENV = green
```

Assim o deploy sempre ocorre no ambiente **oposto ao atual**.

---

# Etapa 4 – Geração do Arquivo `.env`

O pipeline utiliza **credenciais armazenadas no Jenkins**.

Essas credenciais incluem:

* banco de dados
* APIs externas
* URLs de integração

Durante o pipeline:

```
cp .env.example .env
```

Depois as variáveis são adicionadas automaticamente:

```
echo DB_HOST=$DB_HOST >> .env
```

Isso evita que segredos fiquem expostos no repositório.

---

# Etapa 5 – Build da Imagem Docker

O Jenkins executa o build do container da aplicação:

```
docker compose -f docker/docker-compose.yml build app_${NEW_ENV}
```

Durante o build do Dockerfile são executados:

* instalação de dependências PHP
* instalação do Node.js
* instalação das extensões do PHP
* instalação das dependências do Composer
* build do frontend

---

# Dockerfile da Aplicação

A imagem da aplicação é baseada em:

```
php:8.3-fpm
```

Durante o build são executadas as seguintes etapas:

1️⃣ Instalação de dependências do sistema

```
git
curl
zip
unzip
```

2️⃣ Instalação do Node.js

3️⃣ Instalação de extensões PHP

```
pdo_mysql
mbstring
gd
bcmath
```

4️⃣ Instalação do Composer

5️⃣ Instalação das dependências do Laravel

```
composer install
```

6️⃣ Build do frontend

```
npm install
npm run build
```

7️⃣ Configuração de permissões do Laravel

```
storage
bootstrap/cache
```

---

# Etapa 6 – Subir Novo Container

Após o build, o Jenkins inicia o novo ambiente:

```
docker compose up -d app_${NEW_ENV}
```

Isso cria o container:

```
laravel_blue
ou
laravel_green
```

---

# Etapa 7 – Executar Migrations

Antes de liberar a aplicação, as migrations são executadas:

```
php artisan migrate --force
```

Isso garante que o banco esteja compatível com a nova versão.

---

# Etapa 8 – Otimização do Laravel

Depois disso são executados comandos de otimização:

```
php artisan optimize
```

Esse comando gera:

* cache de rotas
* cache de configuração
* cache de views
* autoload otimizado

---

# Etapa 9 – Health Check

Antes de liberar o deploy, o pipeline verifica se a aplicação está funcionando:

```
curl -f http://localhost
```

Se a verificação falhar:

```
deploy é interrompido
```

Isso evita que uma versão quebrada seja colocada em produção.

---

# Etapa 10 – Troca de Ambiente no Nginx

O Jenkins atualiza o arquivo de configuração do Nginx para apontar para o novo container.

Exemplo:

Antes:

```
laravel_blue
```

Depois:

```
laravel_green
```

Após isso o Nginx é recarregado:

```
nginx -s reload
```

O tráfego passa imediatamente para a nova versão da aplicação.

---

# Etapa 11 – Remover Ambiente Antigo

Após a troca, o container antigo é removido:

```
docker stop laravel_${OLD_ENV}
docker rm laravel_${OLD_ENV}
```

Isso libera recursos do servidor.

---

# Gerenciamento de Credenciais

As credenciais são armazenadas no **Jenkins Credentials Manager**.

Exemplos:

| Credencial       | Descrição           |
| ---------------- | ------------------- |
| DB_HOST          | Host do banco       |
| DB_DATABASE      | Nome do banco       |
| DB_USERNAME      | Usuário             |
| DB_PASSWORD      | Senha               |
| CLICKUP_API_KEY  | Integração ClickUp  |
| REDTRACK_API_KEY | Integração RedTrack |

As credenciais são **injetadas dinamicamente no pipeline**.

---

# Conclusão

O pipeline CI/CD do **Titan Agil** utiliza uma arquitetura moderna baseada em:

* Jenkins
* Docker
* Blue-Green Deployment
* Nginx

Essa estrutura garante que o deploy da aplicação seja:

* automatizado
* seguro
* rápido
* escalável
* sem downtime
