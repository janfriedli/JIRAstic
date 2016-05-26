# JIRAstic
JIRAstic automatically creates a sprint resumée with RevealJS.

This software was created during my IPA.

## Installation

- Clone this repo
- Run ``composer install``
- Make the necessary configuration
- Then ```php app/console server:start```

This will start a development server

## Configuration

### OAuth

1. JIRA uses RSA. Create an Keypair with the following commands:

     ```openssl genrsa -out jira.pem 1024```

     ```openssl rsa -in jira.pem -pubout -out jira.pub```

2. Follow the steps in this tutorial to enable OAuth for JIRAstic: https://www.prodpad.com/2013/05/tech-tutorial-oauth-in-jira/

3. Add the missing params to ``app/config/paramters.yml`` or enter them during composer install
    * jira.consumer_key -> Consumer Key
    * jira.consumer_secret -> The consumer secret
    * jira.private_key_path -> Absolute path to your private key file
    * jirastic_api_url -> the URL to your JIRA instance. *NO* trailing slash at the end.

### Database

JIRAstic uses a MySQL Database. Configure the necessary details trough ```app/config/paramters.yml``` if not already done when running ```composer install```.

Make sure the databasename doesn't already exist and run:

```php app/console doctrine:database:create```

and then: 

```php app/console doctrine:schema:update --force```

## Unit Tests

To run the Unit Test:

```phpunit ```

This assumes you have PHPUnit installed.
ToDo: Write unit test ;)
