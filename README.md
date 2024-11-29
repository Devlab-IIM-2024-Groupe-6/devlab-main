# BAP-8

## Installation
1. Clone the repository
````shell
$ https://github.com/Devlab-IIM-2024-Groupe-6/devlab-main.git
````
2. Go to the project directory
````shell
$ cd devlab-main
````
3. Install composer
````shell
$ composer install
````
4. Install all asset mapper packages
````shell
$ php bin/console importmap:install
````

5. Duplicate the `.env` file and name it `.env.local`
6. Update the `DATABASE_URL` variable in the `.env.local` file with your database credentials. It should look like this : <br>
```php
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```
7. Create the database
````shell
$ php bin/console doctrine:database:create
````
8. Run the migrations
````shell
$ php bin/console doctrine:migrations:migrate
````

9. Load the fixtures
```shell
$ php bin/console doctrine:fixtures:load
```
10. Run the server
````shell 
$ symfony serve
````
11. Run the tailwind watch command
````shell 
$ composer tailwind-watch
````
12. Go to http://127.0.0.1:8000

13. Enjoy!

## Accéder a l'admin : http://127.0.0.1:8000/admin <br>

  Mail : curiouslab@admin.com <br>
  Mot de passe : aMLBkroE6gJmS&$$ <br>