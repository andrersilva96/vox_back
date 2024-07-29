# Vox Backend project with Symfony and PostgreSQL

## Description

This project is a company registration and corporate structure system developed with Symfony 7.1 and PostgreSQL. The system includes authentication, authorization, and CRUD for company and user entities.

## Requirements

- **PostgreSQL 15+**: Relational database used to store data.
- **Symfony 7.1**: PHP Framework for application development.
- **PHP 8.2**: PHP version required by Symfony.
- **Composer**: Dependency manager for PHP.
- **Node.js and npm**: To manage frontend packages (optional, if using a frontend).

## Documentation
[Here is the documentation.](https://documenter.getpostman.com/view/10880762/2sA3kaCK9z)

## Installation

### Step 1: Clone the Repository

````
$ git clone git@github.com:andrersilva96/vox_back.git && cd vox_back
````

### Step 2: Configure the Environment
Install PHP Dependencies

Make sure Composer is installed. Install project dependencies:

````
$ composer install
````

### Step 2: Configure Database

In your .env from root at project configure like bellow:

````
DATABASE_URL=pgsql://username:password@localhost:5432/db_name
````

Replace username, password, localhost, 5432 and bank_name with the appropriate information from your PostgreSQL environment.

### Step 3: Run migrations

````
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
````

### Step 4: Run seeders

````
$ php bin/console doctrine:fixtures:load
````

### Step 5: Generate JWT Key

In your .env from root at project generate a hash for ``JWT_PASSPHRASE``.

### Step 6: Generate JWT Key

If using JWT authentication, generate the keys:

````
$ php bin/console lexik:jwt:generate-keypair
````

### Step 7: Execute the server
Start the Symfony built-in server for development:

````
$ symfony server
````
