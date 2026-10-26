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
                    # Stop old container if exists
                    docker stop laravel_app_tier || true
                    docker rm laravel_app_tier || true
                    
                    # Run app container directly using pre-cached runtime
                    docker run -d --name laravel_app_tier \
                      --network laravel-app_app_network \
                      -v $(pwd):/var/www/html \
                      -p 8000:8000 \
                      php:8.2-alpine php -S 0.0.0.0:8000 -t /var/www/html/public
                '''
            }
        }

        stage('Verify 3-Tier Status') {
            steps {
                sh '''
                    sleep 3
                    docker ps --filter "name=laravel_"
                    curl -I http://localhost:8000 || true
                '''
            }
        }
    }
}
