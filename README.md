# Currency Converter

### Prerequisites:
Have docker and docker-compose installed.

### Steps

- Run command `make start`. This will build and start the docker network.
- Run command `composer install`
- Run command `make bash`, then run `php bin/console doctrine:migration:migrate`. This will setup the database
- In bash run command `php bin/console app:exchange-rate:fetch`. This will fetch all exchange rates
- In your browser go to http://localhost:8080/register to register as new user.

