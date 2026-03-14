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
                docker compose -f docker/docker-compose.yml down
                docker compose -f docker/docker-compose.yml up -d --build
                '''
            }
        }

        stage('Gerar APP_KEY') {
            steps {
                sh 'docker exec laravel_app php artisan key:generate'
            }
        }

        stage('Laravel Migrate') {
            steps {
                sh 'docker exec laravel_app php artisan migrate --force'
            }
        }

        stage('Laravel Optimize') {
            steps {
                sh '''
                docker exec laravel_app php artisan optimize:clear
                docker exec laravel_app php artisan config:cache
                docker exec laravel_app php artisan route:cache
                docker exec laravel_app php artisan view:cache
                '''
            }
        }


    }
}
