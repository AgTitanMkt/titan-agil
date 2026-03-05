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

        stage('Instalar Dependências PHP') {
            steps {
                sh '''
                composer install --no-interaction --prefer-dist
                '''

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
                    string(credentialsId: 'REDTRACK_API_KEY', variable: 'REDTRACK_API_KEY')
                ]) {
                    sh '''
                    cp .env.example .env

                    printf "\nDB_HOST=%s\n" "$DB_HOST" >> .env
                    printf "DB_DATABASE=%s\n" "$DB_DATABASE" >> .env
                    printf "DB_USERNAME=%s\n" "$DB_USERNAME" >> .env
                    printf "DB_PASSWORD=%s\n" "$DB_PASSWORD" >> .env

                    printf "CLICKUP_BASE_URL=%s\n" "$CLICKUP_BASE_URL" >> .env
                    printf "CLICKUP_API_KEY=%s\n" "$CLICKUP_API_KEY" >> .env

                    printf "REDTRACK_BASE_URL=%s\n" "$REDTRACK_BASE_URL" >> .env
                    printf "REDTRACK_API_KEY=%s\n" "$REDTRACK_API_KEY" >> .env
                    '''
                }
            }
        }


        stage('Gerar APP_KEY') {
            steps {
                sh 'php artisan key:generate'
            }
        }

        stage('Gerar Build Frontend') {
            steps {
                sh '''
                npm install
                npm run build
                '''
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                rsync -avz --delete \
                --exclude=.env \
                --exclude=storage \
                --exclude=vendor \
                --exclude=node_modules \
                ./ /var/www/laravel-dev

                cd /var/www/laravel-dev
                php artisan config:cache
                php artisan view:cache
                '''
            }
        }

    }
}
