pipeline {
    agent any

    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }

        stage('Deploy Containers') {
            steps {
                sh '''
                    # Stop existing app container if running
                    docker stop laravel_app_tier || true
                    docker rm laravel_app_tier || true
                    
                    # Build and run using native Docker commands (No compose dependency)
                    docker build -t laravel-app:latest .
                    docker run -d --name laravel_app_tier --network laravel-app_app_network -p 8000:8000 laravel-app:latest
                '''
            }
        }

        stage('Verify 3-Tier Status') {
            steps {
                sh '''
                    sleep 5
                    docker ps --filter "name=laravel_"
                    curl -I http://localhost:8000 || true
                '''
            }
        }
    }
}
