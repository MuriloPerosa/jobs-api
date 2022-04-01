## Installation

1. Clone repository. <br/>
2. Duplicate `.env.example` and rename the copy to `.env`. <br/>
3. Navigate to project folder and run the following commands on terminal:

```ssh
sudo docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
```ssh
./vendor/bin/sail up -d
```
```ssh
./vendor/bin/sail artisan key:generate
```
```ssh
./vendor/bin/sail artisan migrate --seed
```
4. Add the following line to the hosts file of your operating system.
> sudo nano /etc/hosts
```ssh
127.0.0.1 jobs.test
```

5. Ready, now the system is working under the following URL: [http://jobs.test:82](http://jobs.test:82)
##

## Local Database
```ssh
HOST=127.0.0.1
PORT=3310
DATABASE=jobs
USERNAME=sail
PASSWORD=password
```
## Tests

To run the tests, use the following command on terminal:

```ssh
./vendor/bin/sail artisan test
```

## Postman
Use the following link to import API Collection: [https://www.getpostman.com/collections/c603f3ccb441a9d10231](https://www.getpostman.com/collections/c603f3ccb441a9d10231).
