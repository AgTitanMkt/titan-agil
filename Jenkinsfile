pipeline {
    agent any

    environment {
        APP_ENV = 'dev'
    }

    stages {

        stage('Clonar Repositório') {
            steps {
                echo 'Clonando projeto...'
            }
        }

        stage('Instalar Dependências') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Gerar .env se não existir') {
            steps {
                withCredentials([
                    string(credentialsId: 'DB_HOST', variable: 'DB_HOST'),
                    string(credentialsId: 'DB_USERNAME', variable: 'DB_USERNAME'),
                    string(credentialsId: 'DB_PASSWORD', variable: 'DB_PASSWORD')
                ]) {
                    sh '''
                    if [ ! -f .env ]; then
                        cp .env.example .env
                    fi

                    sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
                    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
                    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
                    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
                    '''
                }
            }
        }


        stage('Gerar APP_KEY') {
            steps {
                sh 'php artisan key:generate'
            }
        }


        stage('Deploy') {
            steps {
                echo 'Iniciando deploy...'
                sh '''
                rsync -av --delete ./ /var/www/laravel-dev
                php artisan key:generate --force
                php artisan config:cache
                php artisan view:cache
                '''
            }
        }

    }
}
