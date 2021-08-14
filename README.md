# ToDoList

[![Maintainability](https://api.codeclimate.com/v1/badges/a74839157bc8a01a7ca1/maintainability)](https://codeclimate.com/github/OlivierFL/Floch_Olivier_8_19062021/maintainability)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OlivierFL_Floch_Olivier_8_19062021&metric=alert_status)](https://sonarcloud.io/dashboard?id=OlivierFL_Floch_Olivier_8_19062021)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=OlivierFL_Floch_Olivier_8_19062021&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=OlivierFL_Floch_Olivier_8_19062021)

# Project

Project 8 - Update and improve an existing project

## Requirements

Mandatory :

- `PHP >= 8.0`
- `Symfony CLI`

Optional :

- `Make` to use the [_Makefile_](./Makefile) and custom commands
- `Docker` and `Docker-compose` for _MySQL_ database and _PhpMyAdmin_ containers

Unit Tests :

- `PHPUnit`

## Installation

1. To get this project on your local machine, simply clone this repository :
   ```shell
   git clone git@github.com:OlivierFL/Floch_Olivier_8_19062021.git
   ```


2. Install the dependencies :

- `composer install`


3. Environment configuration :

   To configure local dev environment, create a `.env.local` file at the root of the project.

   To configure database connection, override the `DATABASE_URL` env variable with your database credentials and database name, for example :

    ```dotenv
    DATABASE_URL="mysql://root:root@127.0.0.1:3306/todo?serverVersion=5.7"
    ```

   If you're using the _MySQL_ Docker container, the config is :

    ```dotenv
    DATABASE_URL="mysql://root:admin@127.0.0.1:3306/todo"
    ```

4. After configuring the database connection, run `bin/console doctrine:database:create` to create the database, then `bin/console doctrine:schema:create` to create database tables.


5. Then run `bin/console doctrine:fixtures:load` to load the example data into the database. If you're using _Docker_, _PhpMyAdmin_ is available on `localhost:8080` (_user_ : `root`, _password_ : `admin`).

6. By default

7.Start the Symfony server with `symfony server:start -d`.

The ___base url___ for the API is : `localhost:8000`.

## Usage

List of useful commands to use the project :

- `symfony server:start` to start the Symfony server
- `symfony server:stop` to stop the Symfony server

Commands to use with _Docker_ and _Make_ (commands are available in _Makefile_ at the root of the project) :

- `make up` to start _Docker_ stack (_MySQL_ and _PhpMyAdmin_) and _Symfony server_
- `make install` to run installation process automatically (manual [environment configuration](#Installation) is needed __before__ running this command)
- `make tests` to run all _PHPUnit_ tests, the database is reset before running the tests (see [**tests**](#tests)) to know how to configure the environment to run the tests
- `make tests-coverage` to run all _PHPUnit_ tests with code coverage
- `make tests-functional` to run only functional _PHPUnit_ tests
- `make tests-entity` to run only _PHPUnit_ unit tests for entities
- `make tests-no-reset` to run all _PHPUnit_ tests, without resetting the database
- `make down` to stop _Docker_ stack (_MySQL_ and _PhpMyAdmin_) and _Symfony server_

## Sample data

In order to have a fully functional application, the fixtures contains :

- 2 Users with different roles :
    - an Admin user (with `ROLE_ADMIN` role) with __admin@example.com__ _email_, and __admin1234__ _password_.

    - a simple User (with `ROLE_USER` role) with __user@example.com__ _email_, and __user1234__ _password_.


- A default list of Tasks (36 items). 


- 10 Tasks without an author. By default, and to show how this issue is fixed by this [pull request](https://github.com/OlivierFL/Floch_Olivier_8_19062021/pull/13), 10 Tasks do not have a User. To fix this, use the Console Command : `bin/console task:set-user`. This command creates an _Anonymous User_ if it does not exist in database, and links this user to the Tasks without an author.


- A default list of Users (26 items). If the above Console Command has been run, an _Anonymous User_ has been added in the database (27 items).

## Third party libraries

Third party libraries used in this project :

- [Faker PHP](https://github.com/FakerPHP/Faker/) to generate fake data for fixtures
- [Psalm](https://github.com/vimeo/psalm) static code analysis
- [PHPStan](https://github.com/phpstan/phpstan) static code analysis
- [Easy Coding Standards](https://github.com/symplify/easy-coding-standard) to check and fix code syntax, bugs, etc.

## Docker (optional)

This project uses Docker for _MySQL_ database and _PhpMyAdmin_.

The stack is composed of 2 containers :

- mysql
- phpMyAdmin

The configuration is available in the [docker-compose.yaml](./docker-compose.yaml).

## Tests

_PhpUnit_ is used to run the tests.

Before running the tests, and to have a separate database for tests, create a `.env.test.local` file at the root of the project.

In this file, add a `DATABASE_URL` env variable to change the database name, for example :

```dotenv
DATABASE_URL="mysql://root:admin@127.0.0.1:3306/todo_tests"
```

Then, run these commands in a terminal to create the database used for the tests, and load the fixtures in the test's database :

```shell
APP_ENV=test symfony console doctrine:database:create
APP_ENV=test symfony console doctrine:schema:create
APP_ENV=test symfony console doctrine:fixtures:load -n
```

When using the `make tests` command from the [Makefile](./Makefile), these commands will be run automatically.

Finally, in a terminal, at the root of the project, run `APP_ENV=test symfony php bin/phpunit --colors` or `make tests`.

It's possible to generate code coverage report by running `APP_ENV=test symfony php bin/phpunit --colors --coverage-html tests-coverage` or `make tests-coverage`. The `tests-coverage` directory will be created, and the report will be available in a browser by opening the `index.html` file from this directory.

## Code quality

Links to code quality tools used for this project:

Codeclimate : https://codeclimate.com/github/OlivierFL/Floch_Olivier_8_19062021

SonarCloud : https://sonarcloud.io/dashboard?id=OlivierFL_Floch_Olivier_8_19062021
