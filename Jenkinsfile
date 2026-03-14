pipeline {
    agent any

    stages {

        stage('Fix Workspace Permissions') {
            steps {
                sh '''
                sudo chown -R jenkins:jenkins /var/lib/jenkins/workspace || true
                sudo chmod -R 775 /var/lib/jenkins/workspace || true
                '''
            }
        }

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
                ])  {
                    sh '''
                    cp .env.example .env

                    echo DB_HOST=$DB_HOST >> .env
                    echo DB_DATABASE=$DB_DATABASE >> .env
                    echo DB_USERNAME=$DB_USERNAME >> .env
                    echo DB_PASSWORD=$DB_PASSWORD >> .env

                    echo CLICKUP_BASE_URL=$CLICKUP_BASE_URL >> .env
                    echo CLICKUP_API_KEY=$CLICKUP_API_KEY >> .env

                    echo REDTRACK_BASE_URL=$REDTRACK_BASE_URL >> .env
                    echo REDTRACK_API_KEY=$REDTRACK_API_KEY >> .env
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

        stage('Fix Docker Permissions') {
            steps {
                sh '''
                docker exec laravel_app chown -R jenkins:jenkins /var/www || true
                '''
            }
        }


        stage('Fix Permissions') {
            steps {
                sh '''
                docker exec laravel_app mkdir -p storage/framework/cache
                docker exec laravel_app mkdir -p storage/framework/views
                docker exec laravel_app mkdir -p storage/framework/sessions

                docker exec laravel_app chown -R www-data:www-data storage bootstrap/cache
                docker exec laravel_app chmod -R 775 storage bootstrap/cache
                '''
            }
        }

        stage('Install Composer') {
            steps {
                sh 'docker exec laravel_app composer install --no-dev --optimize-autoloader'
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

        stage('Frontend Build') {
            steps {
                sh '''
                    docker exec laravel_app npm install
                    docker exec laravel_app npm run build
                    '''
            }
        }

        stage('Laravel Optimize') {
            steps {
                sh '''
                docker exec laravel_app php artisan optimize:clear
                docker exec laravel_app php artisan optimize
                '''
            }
        }

    }
}
