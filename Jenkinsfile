pipeline {
    agent any

    environment {
        APP_ENV = 'dev'
    }

    stages {

        stage('Instalar Dependências') {
            steps {
                sh 'composer install --no-interaction --prefer-dist --no-dev'
            }
        }

        stage('Gerar .env') {
            steps {
                withCredentials([
                    string(credentialsId: 'DB_HOST', variable: 'DB_HOST'),
                    string(credentialsId: 'DB_USERNAME', variable: 'DB_USERNAME'),
                    string(credentialsId: 'DB_PASSWORD', variable: 'DB_PASSWORD')
                ]) {
                    sh '''
                    cp .env.example .env

                    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
                    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
                    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
                    '''
                }
            }
        }

        stage('Build Test') {
            steps {
                sh 'php artisan config:clear'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                rsync -av --delete --exclude=.env ./ /var/www/laravel-dev

                cd /var/www/laravel-dev

                php artisan key:generate --force
                php artisan migrate --force
                php artisan config:cache
                php artisan view:cache
                '''
            }
        }
    }
}
