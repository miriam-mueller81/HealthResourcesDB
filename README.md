# HealthResourcesDB

## Installation:

### Preconditions:
- install PHP
- install MySQL
- install composer (https://getcomposer.org/)
- install symfony (https://symfony.com/download)

### Install instructions:
- Clone repository
- cd HealthResourcesDB
- cd backend
- composer install
- symfony server:start
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate

### Test your setup:
- open 'http://localhost:8000/dummy/index' in your browser
- a webside with a JSON browser should be shown

## API:

### Article:
- List all articles: 
    - Url: `http://localhost:8000/api/article`
    - Method: GET
