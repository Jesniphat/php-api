My API Freamwork
=================================================
Build by pure php and use alwex/php-database-migration https://github.com/alwex/php-database-migration for migration DB

Installing it to your project
-----------------------------
Clone project from git.
Install pagkage.
Run migrate db stuff
```
    RUN:
    git clone git@github.com:Jesniphat/php-api.git

    RUN:
    composer update 

    RUN:
    ./bin/migrate migrate:addenv
    ./bin/migrate migrate:init [env]
```


Dev
-------------------------------
Run for dev
```
    RUN:
        php -S localhost:8090 -t ./api
```

Prod
-------------------------------
Need docker compose

For nginx: Change docker-compose(nginx).yml to docker-compose.yml
```
    RUN:
    docker-compose up -d --build
```

For apache
```
    RUN:
    docker-compose up -d --build
```

:note -> need to use mysql 5 for now
    