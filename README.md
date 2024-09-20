## Laravel Coding Challenge

The original code of the challenge included database migrations, models and empty controllers/tests. My task was to create REST API endpoints for managing players and additional endpoint for finding best players for a team based on required positions and skills. In addition to this, tests had to be created to test the API endpoints. API endpoints do not have auth as that wasn't a requirement. My code can be found in the following files:

/app/Http/Controllers/*
/app/Http/Requests/*
/app/Http/Resources/*
/app/Enums/*
/app/Rules/*
/tests/Feature/*

### Requirements
- PHP = 8.1
- Laravel >= 9.14
- SQLite database

 This challenge does not require any additional library. DO NOT MODIFY the composer.json or composer.lock file as that may result in a test failure.
 The project already contain a sample SQLite database at /database/database.sqlite. Please don´t change the database structure by creating a seed or migration file because this may also result in a test failure.

### Installation

- Run composer install command: `composer install`

- To serve the api run the command: `php artisan serve --port=3000`

- To run the tests use the command: `php artisan test`
