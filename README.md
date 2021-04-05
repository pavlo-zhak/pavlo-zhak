# Test task docker env

### Project files
You can find project files in "web" directory if you want to run the project in another environment

### Base commands
``make init`` - Initialize docker, pull images, init db, install composer dependencies.  
``make docker-start`` - Start docker  
``make docker-stop`` - Stop docker containers  
``make logs`` - get containers logs  

### PMA

For log in PMA:  
``server`` - leave blank  
``username`` - root  
``password`` - root

### For application DB config

``'hostname' => 'frozeneon-mysql',
'username' => 'dev',
'password' => 'dev',
'database' => 'test_task',``