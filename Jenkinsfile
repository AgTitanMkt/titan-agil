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
                string(credentialsId: 'APP_KEY', variable: 'APP_KEY'),
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
                cd /var/lib/jenkins/workspace/Titan-Agil

                cp .env.example .env

                sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|" .env

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

    stage('Frontend Build') {
        steps {
            sh '''
            cd /var/lib/jenkins/workspace/Titan-Agil

            npm install
            npm run build
            '''
        }
    }

    stage('Build + Deploy') {
        steps {
            sh '''
            cd /var/lib/jenkins/workspace/Titan-Agil

            docker compose -p laravel_docker -f docker/docker-compose.yml down
            docker compose -p laravel_docker -f docker/docker-compose.yml up -d --build

            sleep 10
            '''
        }
    }

    stage('Laravel Setup') {
        steps {
            sh '''
            cd /var/lib/jenkins/workspace/Titan-Agil

            docker compose -p laravel_docker -f docker/docker-compose.yml exec -T app php artisan config:clear
            docker compose -p laravel_docker -f docker/docker-compose.yml exec -T app php artisan cache:clear
            docker compose -p laravel_docker -f docker/docker-compose.yml exec -T app php artisan migrate --force
            docker compose -p laravel_docker -f docker/docker-compose.yml exec -T app php artisan optimize
            '''
        }
    }

    stage('Verificação Final') {
        steps {
            sh '''
            cd /var/lib/jenkins/workspace/Titan-Agil

            docker compose -p laravel_docker -f docker/docker-compose.yml exec -T app php artisan --version
            '''
        }
    }
}

post {
    failure {
        sh '''
        cd /var/lib/jenkins/workspace/Titan-Agil

        docker compose -p laravel_docker -f docker/docker-compose.yml logs app | tail -50
        '''
    }
}

}
