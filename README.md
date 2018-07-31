# Laravel Validation Generator

Automatically add the validations for tables in the database

## Installation

The package can easily be installed by running `composer require vigneshc91/laravel-validation-generator` in your project's root folder.

If you are running a version of Laravel < 5.5 also make sure you add `Vigneshc91\LaravelValidationGenerator\ValidationGeneratorServiceProvider::class` to the `providers` array in `config/app.php`.

This will register the artisan command that will be available to you.


## Usage

Generating the validation is easy, simply run `php artisan laravel-validation:generate` in your project root. This will create a request file and save it in the requests directory.

If you wish to generate validation only for specific tables, you can pass a table using --tables, for example `php artisan laravel-validation:generate --tables='users,addresses'`

If you wish to ignore specific tables, you can pass a ignore-table using --ignore-tables, for example `php artisan laravel-validation:generate --ignore-tables='users,addresses'`

If you wish to ignore specific columns, you can pass a ignore-columns using --ignore-columns, for example `php artisan laravel-validation:generate --ignore-columns='user_id,address_id'`

If you wish to change the directory of creating the request file, you can pass a directory using --dir, for example `php artisan laravel-validation:generate --dir='V1'`

If you wish to change the suffix of creating the request file, you can pass a suffix using --suffix, for example `php artisan laravel-validation:generate --suffix='Create'`

If you wish to change the format of the output, you can pass a format using --format, for example `php artisan laravel-validation:generate --format='file'`

Format options are:<br>
`file`<br>
`console`