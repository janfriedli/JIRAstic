# JIRAstic
IPA Project

## Installation

- clone this repo
- Run ```composer install``
- Make the necessary configuration
- Then ```php app/console server:start```

This will start a development server

## Configuration

### Security

1. Change Usernames and passwords in the ```app/config/security.yml``` file.

### Database

JIRAstic uses MySQL. Configure the necessary details trough ```app/config/paramters.yml``` if not already done when running ```composer install```.

Make sure the databasename doesn't already exist and run:

```php app/console doctrine:database:create```

and then: 

```php app/console doctrine:schema:update --force```

## Unit Tests

To run the Unit Test:

```phpunit ```

This assumes you have PHPUnit installed.
