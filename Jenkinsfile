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

        stage('Build Image') {
            steps {
                sh 'docker compose -f docker/docker-compose.yml build app_${NEW_ENV}'
            }
        }

        stage('Start Container') {
            steps {
                sh 'docker compose -f docker/docker-compose.yml up -d app_${NEW_ENV}'
            }
        }

        stage('Run Migrations') {
            steps {
                sh '''
                sleep 10
                docker exec laravel_${NEW_ENV} php artisan migrate --force
                '''
            }
        }

        stage('Optimize Laravel') {
            steps {
                sh '''
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
