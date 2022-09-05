# blogsite PHP Symfony
Create a docker container and run it by using

```bash
docker compose up -d --build
```

Create the database
Follow the instructions at Symfony's website https://symfony.com/doc/current/doctrine.html to create a database and save the entities to the database.

```bash
docker exec -it blog-server sh -c "bin/console d:m:m"
```

The above command runs the migration files, creating the database.

Afterwards in your browser, go to localhost and create an account and enjoy the blogsite.

To connect to the database through an application, i.e DBeaver CE, the following image should be sufficient. The database name, username and root password in this example are located inside the file _docker-compose.yml._ 
![image](https://user-images.githubusercontent.com/83977384/188494939-e3805fd5-c1fa-42f9-8445-bb6a74d353e0.png)
