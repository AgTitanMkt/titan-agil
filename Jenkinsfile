pipeline {
    agent any

    stages {
        stage('Clean Workspace') {
            steps {
                cleanWs()
            }
        }

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Gerar .env') {
            steps {
                withCredentials([
                    string(credentialsId: 'DB_HOST', variable: 'DB_HOST'),
                    string(credentialsId: 'DB_DATABASE', variable: 'DB_DATABASE'),
                    string(credentialsId: 'DB_USERNAME', variable: 'DB_USERNAME'),
                    string(credentialsId: 'DB_PASSWORD', variable: 'DB_PASSWORD'),
                    string(credentialsId: 'CLICKUP_BASE_URL', variable: 'CLICKUP_BASE_URL'),
                    string(credentialsId: 'CLICKUP_API_KEY', variable: 'CLICKUP_API_KEY'),
                    string(credentialsId: 'REDTRACK_BASE_URL', variable: 'REDTRACK_BASE_URL'),
                    string(credentialsId: 'REDTRACK_API_KEY', variable: 'REDTRACK_API_KEY'),
                ]) {
                    sh '''
                    cp .env.example .env

                    sed -i "s|DB_HOST=.*|DB_HOST=$DB_HOST|" .env
                    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|" .env
                    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" .env
                    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env

                    sed -i "s|CLICKUP_BASE_URL=.*|CLICKUP_BASE_URL=$CLICKUP_BASE_URL|" .env
                    sed -i "s|CLICKUP_API_KEY=.*|CLICKUP_API_KEY=$CLICKUP_API_KEY|" .env

                    sed -i "s|REDTRACK_BASE_URL=.*|REDTRACK_BASE_URL=$REDTRACK_BASE_URL|" .env
                    sed -i "s|REDTRACK_API_KEY=.*|REDTRACK_API_KEY=$REDTRACK_API_KEY|" .env
                    '''
                }
            }
        }

        stage('Build Containers') {
            steps {
                sh '''
                docker compose -p laravel_docker -f docker/docker-compose.yml up -d --build
                echo "Aguardando containers iniciarem..."
                sleep 10
                '''
            }
        }

        stage('Laravel Install') {
            steps {
                sh 'docker compose -p laravel_docker exec -T app composer install --no-dev --optimize-autoloader'
            }
        }

        stage('Frontend Build') {
            steps {
                sh '''
                echo "Instalando dependências npm..."
                docker compose -p laravel_docker exec -T app npm install --verbose || { echo "❌ npm install falhou"; exit 1; }
                
                echo "Buildando assets com Vite..."
                docker compose -p laravel_docker exec -T app npm run build --verbose || { echo "❌ npm run build falhou"; exit 1; }
                
                echo "Verificando se assets foram gerados..."
                docker compose -p laravel_docker exec -T app test -d public/build || { echo "❌ Pasta public/build não foi criada!"; exit 1; }
                docker compose -p laravel_docker exec -T app test -f public/build/manifest.json || { echo "❌ manifest.json não foi gerado!"; exit 1; }
                
                echo "Assets gerados com sucesso!"
                docker compose -p laravel_docker exec -T app ls -la public/build/ | head -20
                '''
            }
        }

        stage('Gerar APP_KEY') {
            steps {
                sh 'docker compose -p laravel_docker exec -T app php artisan key:generate'
            }
        }

        stage('Laravel Migrate') {
            steps {
                sh 'docker compose -p laravel_docker exec -T app php artisan migrate --force'
            }
        }

        stage('Laravel Optimize') {
            steps {
                sh '''
                docker compose -p laravel_docker exec -T app php artisan optimize:clear
                docker compose -p laravel_docker exec -T app php artisan config:cache
                docker compose -p laravel_docker exec -T app php artisan route:cache
                docker compose -p laravel_docker exec -T app php artisan view:cache
                '''
            }
        }

        stage('Verificação Final') {
            steps {
                sh '''
                echo "Verificando aplicação..."
                docker compose -p laravel_docker exec -T app php artisan --version
                echo "Assets gerados:"
                docker compose -p laravel_docker exec -T app find public/build -type f | wc -l
                echo "Deploy concluído com sucesso!"
                '''
            }
        }
    }

    post {
        failure {
            sh '''
            echo "Deploy falhou!"
            echo "Verificando logs do Docker..."
            docker compose -p laravel_docker logs app | tail -50
            '''
        }
    }
}
