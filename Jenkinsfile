pipeline {
    agent { label 'buildserver' }
    stages {
        stage('Checkout SDK tester code') {
			steps {
				checkout(
					[$class: 'GitSCM', branches: [[name: '*/master']], 
					doGenerateSubmoduleConfigurations: false,
					extensions: [], 
					submoduleCfg: [], 
					userRemoteConfigs: [[url: 'https://github.com/krishnakumarkp/sdk-tester.git']]]
				)
			}
        }
        stage('Prepare') {
            steps {
                sh 'rm -rf vendor'
                sh 'rm -rf composer.lock'
            }
        }
		stage('Install php sdk in sdk tester') {
			steps {
				sh 'wget -q https://getcomposer.org/download/1.10.10/composer.phar'
				sh 'php composer.phar install --prefer-dist --no-progress'
            }
        }
		stage('Deploy sdk tester') {
            steps {
                sh 'ssh jenkins@192.168.100.142 rm -rf  /var/www/html/myproject/temp_deploy'
				sh 'ssh jenkins@192.168.100.142 mkdir -p /var/www/html/myproject/temp_deploy'
				sh 'scp -r public jenkins@192.168.100.142:/var/www/html/myproject/temp_deploy/public'
				sh 'scp -r src jenkins@192.168.100.142:/var/www/html/myproject/temp_deploy/src'
				sh 'scp -r vendor jenkins@192.168.100.142:/var/www/html/myproject/temp_deploy/vendor'
                sh 'ssh jenkins@192.168.100.142 "rm -rf /var/www/html/myproject/sdk_tester && mv /var/www/html/myproject/temp_deploy/ /var/www/html/myproject/sdk_tester/"'
            }
        }
    }
}