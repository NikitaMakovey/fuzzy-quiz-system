###

#### 1. Prepare ENV file and build docker containers

```shell
docker-compose up -d --build

# proxy -> 8000 (port) -> http://localhost:8000
```

#### 2. Install dependencies, migrate database tables and add initial records

```shell
# jump into PHP container
docker-compose exec app bash

# install Symfony dependencies
composer install

# migrate database tables
./bin/console doctrine:migrations:migrate -n

# add initial records
./bin/console doctrine:fixtures:load -n
```

#### 3. You can use any interface (ex.: browser to test)

##### You can also check [recorded demo](https://www.loom.com/share/d8347634af9749c4a8af01ead77c0af6?sid=8e074f88-adde-4c6a-bf7c-2ecf4c47440b)

```shell
> go to http://localhost:8000/api/quizzes

> choose first one and go to http://localhost:8000/api/quizzes/1

> ready to start? go to http://localhost:8000/api/quizzes/1/start

> your next questions here (response contains next_question if everything is valid)

>> quiz attempt started

> go to http://localhost:8000/api/questions/{next_question}

> check answers and answer using http://localhost:8000/api/questions/{id}/answer?answers[]={value1}&answers[]={value2}

> (or same method for POST with request JSON body)

> (if it is a last question, next action -> redirect to results)

>> quiz attempt completed

> you see your results in response 

> (correct_questions -> with correct answers (only), incorrect_questions -> with wrong answers (any))

> you can retry -> just go to http://localhost:8000/api/quizzes/1/start (as many times as you wish)
```