# loan-repayment-schedule
calculating load repayment schedule 


# requirements
app launched on 
docker version 20.10.11
docker-compose 1.29.2

# running
1. run command 
```
  docker-compose up --build
```

2. 
API endpoint to get token 
 ```
 http://localhost:8000/api/login_check POST 
 {
    "username":"test@domain.com",
    "password":"passasd"
  }
```

3. running tests
- first go to docker container and type in commands
  ```
  cd /app
  ./vendor/bin/phpunit tests/CalculatorTest.php
  ```