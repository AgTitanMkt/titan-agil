# 📄 Documentação – CI/CD do Projeto Titan Agil

## Visão Geral

O processo de **CI/CD (Continuous Integration / Continuous Deployment)** do projeto **Titan Agil** utiliza:

* Jenkins para automação do pipeline
* Docker para containerização da aplicação
* Docker Compose para orquestração dos containers
* Laravel como framework backend
* GitHub como repositório de código

O objetivo do pipeline é **automatizar o processo de deploy**, garantindo que cada atualização enviada ao repositório seja construída, configurada e disponibilizada automaticamente no servidor.

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
Build Docker
   ↓
Start Containers
   ↓
Build Frontend
   ↓
Laravel Optimize
   ↓
Aplicação disponível
```

---

# Estrutura do Projeto

A aplicação possui a seguinte estrutura relevante para o deploy:

```
project-root
│
├─ docker/
│   ├─ docker-compose.yml
│   ├─ php/
│   │   └─ Dockerfile
│   └─ nginx/
│       └─ default.conf
│
├─ Jenkinsfile
├─ .env.example
└─ Laravel Project
```

---

# Jenkins Pipeline

O pipeline é definido através de um **Jenkinsfile** presente no repositório.

Ele executa uma série de etapas automatizadas sempre que um build é iniciado.

Principais etapas:

1. Clonar o repositório
2. Gerar arquivo `.env`
3. Construir containers Docker
4. Subir containers
5. Instalar dependências frontend
6. Otimizar cache do Laravel

---

# Etapa 1 – Clonar Repositório

O Jenkins utiliza a integração com o Git para baixar o código mais recente do repositório.

Exemplo:

```
checkout scm
```

O código é clonado dentro do workspace:

```
/var/lib/jenkins/workspace/Titan-Agil
```

---

# Etapa 2 – Geração do Arquivo `.env`

O pipeline utiliza **credenciais seguras armazenadas no Jenkins**.

Essas credenciais incluem:

* Banco de dados
* API keys externas
* URLs de serviços

Durante o pipeline:

1. O `.env.example` é copiado
2. As variáveis são inseridas automaticamente

Exemplo:

```
cp .env.example .env
echo DB_HOST=$DB_HOST >> .env
```

Isso evita armazenar segredos no repositório.

---

# Etapa 3 – Build dos Containers

O Jenkins executa o build da aplicação utilizando **Docker Compose**.

Comando utilizado:

```
docker compose -f docker/docker-compose.yml build
```

Durante o build:

* O Dockerfile instala dependências PHP
* Instala Node.js
* Instala extensões PHP
* Instala dependências do Composer

---

# Etapa 4 – Subir Containers

Após o build, os containers são iniciados.

Comando:

```
docker compose -f docker/docker-compose.yml up -d
```

Containers principais:

### Aplicação PHP

Container responsável por executar o Laravel.

Baseado em:

```
php:8.3-fpm
```

Responsabilidades:

* Executar PHP
* Processar requisições Laravel
* Executar Artisan commands

---

### Servidor Web

Container baseado em:

```
nginx
```

Responsável por:

* Servir arquivos públicos
* Encaminhar requisições para PHP-FPM

---

# Etapa 5 – Build do Frontend

Se a aplicação utiliza **Vite ou Mix**, o pipeline executa:

```
npm install
npm run build
```

Isso gera os arquivos finais dentro de:

```
public/build
```

---

# Etapa 6 – Otimização do Laravel

Após o deploy, o pipeline executa comandos de otimização.

```
php artisan optimize
```

Esse comando executa:

* cache de configuração
* cache de rotas
* cache de views
* otimização de autoload

Isso melhora o desempenho da aplicação em produção.

---

# Gerenciamento de Credenciais

As credenciais são armazenadas dentro do **Jenkins Credentials Manager**.

Exemplos utilizados no projeto:

| Credencial       | Descrição               |
| ---------------- | ----------------------- |
| DB_HOST          | Host do banco           |
| DB_DATABASE      | Nome do banco           |
| DB_USERNAME      | Usuário do banco        |
| DB_PASSWORD      | Senha do banco          |
| CLICKUP_API_KEY  | Integração com ClickUp  |
| REDTRACK_API_KEY | Integração com RedTrack |

Essas credenciais são injetadas dinamicamente no pipeline.

---

# Estrutura de Containers

Arquitetura atual:

```
Server
│
├─ Jenkins
│
├─ Docker
│   ├─ laravel_app
│   └─ laravel_nginx
```

---

# Benefícios do Pipeline

O pipeline atual proporciona:

* Deploy automatizado
* Ambiente isolado via containers
* Gerenciamento seguro de credenciais
* Reprodutibilidade do ambiente
* Redução de erros humanos

---

# Conclusão

O pipeline implementado automatiza o processo de deploy da aplicação **Titan Agil**, utilizando ferramentas modernas de containerização e integração contínua.

Essa arquitetura garante que o processo de atualização da aplicação seja:

* rápido
* seguro
* reproduzível
* escalável
