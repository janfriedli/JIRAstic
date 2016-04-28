# JIRAstic
IPA Project

## Installation

- clone this repo
- Run ```composer install``
- Make the necessary configuration
- Then ```php app/console server:start```

This will start a development server

## Configuration

### OAuth

1. JIRA use RSA. Create an Keypair:

     ```openssl genrsa -out jira.pem 1024```

     ```openssl rsa -in jira.pem -pubout -out jira.pub```

2. Follow the steps in this tutorial to enable OAuth for JIRAstic: https://www.prodpad.com/2013/05/tech-tutorial-oauth-in-jira/

3. Add the missing params to ``app/config/paramters.yml``
    * jira.client_id -> Consumer Key
    * jira.client_secret -> absolute path to your private key file.
    * jirastic_api_url -> the URL to your JIRA instance. NO trailing slash at the end.
    
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
