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

                echo "Aguardando container app ficar pronto..."

                until docker compose -p laravel_docker exec -T app php -v > /dev/null 2>&1; do
                  sleep 2
                done

                echo "Container pronto"
                '''
            }
        }

        stage('Laravel Install') {
            steps {
                sh '''
                echo "Rodando composer install..."

                docker compose -p laravel_docker exec -T app composer install --no-dev --optimize-autoloader \
                || { echo "composer install falhou"; exit 1; }

                echo "Verificando vendor..."

                docker compose -p laravel_docker exec -T app test -f /var/www/vendor/autoload.php \
                || { echo "vendor não foi gerado"; exit 1; }

                echo "Composer OK"
                '''
            }
        }

        stage('Frontend Build') {
            steps {
                sh '''
                echo "Instalando npm..."
                docker compose -p laravel_docker exec -T app npm install \
                || { echo "npm install falhou"; exit 1; }

                echo "Buildando assets..."
                docker compose -p laravel_docker exec -T app npm run build \
                || { echo "npm build falhou"; exit 1; }

                docker compose -p laravel_docker exec -T app test -d public/build \
                || { echo "build não gerado"; exit 1; }

                echo "Frontend OK"
                '''
            }
        }

        stage('Laravel Setup') {
            steps {
                sh '''
                docker compose -p laravel_docker exec -T app php artisan key:generate --force

                docker compose -p laravel_docker exec -T app php artisan migrate --force

                docker compose -p laravel_docker exec -T app php artisan optimize:clear
                docker compose -p laravel_docker exec -T app php artisan config:cache
                docker compose -p laravel_docker exec -T app php artisan route:cache
                docker compose -p laravel_docker exec -T app php artisan view:cache

                echo "Laravel OK"
                '''
            }
        }

        stage('Verificação Final') {
            steps {
                sh '''
                echo "Verificando app..."

                docker compose -p laravel_docker exec -T app php artisan --version

                docker compose -p laravel_docker exec -T app ls -la public/build | head -20

                echo "Deploy finalizado com sucesso"
                '''
            }
        }
    }

    post {
        failure {
            sh '''
            echo "Deploy falhou"
            docker compose -p laravel_docker logs app | tail -50
            '''
        }
    }
}