pipeline {
    agent any

    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }

        stage('Deploy Three-Tier Containers') {
            steps {
                sh '''
                    docker compose down || true
                    docker compose up --build -d
                '''
            }
        }

        stage('Run DB Migration & Verify Access') {
            steps {
                sh '''
                    sleep 15
                    docker compose exec -T app php artisan key:generate --force
                    docker compose exec -T app php artisan migrate --force
                    docker compose exec -T app php artisan migrate:status
                    curl -I http://localhost:8000 || true
                '''
            }
        }
    }
}
