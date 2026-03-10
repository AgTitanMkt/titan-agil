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

        stage('Detect Active Environment') {
            steps {
                script {
                    ACTIVE = sh(
                        script: "docker ps --format '{{.Names}}' | grep laravel_blue || true",
                        returnStdout: true
                    ).trim()

                    if (ACTIVE) {
                        env.NEW_ENV = "green"
                        env.OLD_ENV = "blue"
                    } else {
                        env.NEW_ENV = "blue"
                        env.OLD_ENV = "green"
                    }
                }
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


        stage('Build Image') {
            steps {
                sh 'docker compose -f docker/docker-compose.yml build app_${NEW_ENV}'
            }
        }

        stage('Start New Containers') {
            steps {
                sh 'docker compose -f docker/docker-compose.yml up -d app_${NEW_ENV}'
            }
        }

        stage('Laravel Migrate') {
            steps {
                sh '''
                docker exec laravel_${NEW_ENV} php artisan migrate --force
                '''
            }
        }

        stage('Laravel Optimize') {
            steps {
                sh '''
                docker exec laravel_${NEW_ENV} php artisan optimize:clear
                docker exec laravel_${NEW_ENV} php artisan optimize
                '''
            }
        }

        stage('Health Check') {
            steps {
                sh '''
                curl -f http://localhost || exit 1
                '''
            }
        }

        stage('Switch Nginx') {
            steps {
                sh '''
                sed -i "s/server laravel_.*/server laravel_${NEW_ENV}:9000;/" docker/nginx/default.conf
                docker exec laravel_nginx nginx -s reload
                '''
            }
        }

        stage('Remove Old Container') {
            steps {
                sh '''
                docker stop laravel_${OLD_ENV} || true
                docker rm laravel_${OLD_ENV} || true
                '''
            }
        }
    }
}
