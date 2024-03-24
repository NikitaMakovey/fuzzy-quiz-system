###

#### 1. Prepare .env and build docker containers

```shell
docker-compose up -d --build
```

#### 2. Setup database and add initial records

```shell
symfony console doctrine:migrations:migrate

symfony console doctrine:fixtures:load
```