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
3. Install composer packages
````shell
$ composer install
````
4. Install npm packages
````shell
$ npm install 
````

5. Install all asset mapper packages
````shell
$ php bin/console importmap:install
````

6. Duplicate the `.env` file and name it `.env.local`
7. Update the `DATABASE_URL` variable in the `.env.local` file with your database credentials. It should look like this : <br>
```php
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```
8. Create the database
````shell
$ php bin/console doctrine:database:create
````
9. Run the migrations
````shell
$ php bin/console doctrine:migrations:migrate
````

10. Load the fixtures
```shell
$ php bin/console doctrine:fixtures:load
```
11. Run the server
````shell 
$ symfony serve
````
12. Run the tailwind watch command
````shell 
$ composer watch
````
13. Go to http://127.0.0.1:8000

14. Enjoy!

## Accéder a l'admin : http://127.0.0.1:8000/admin <br>

  Mail : curiouslab@admin.com <br>
  Mot de passe : aMLBkroE6gJmS&$$ <br>

  Mail: depot@admin.com 
  Mot de passe : aMLBkroE6gJmS&$$