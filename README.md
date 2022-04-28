# blogsite PHP Symfony
Create a docker container and run it by using

```bash
docker compose up -d --build
```

Create the database

```bash
docker exec -it blog-server sh -c "bin/console doctrine:migrations:migrate"
```

The above command runs the migration files, creating the database.

Afterwards in your browser, go to localhost and create an account and enjoy the blogsite.