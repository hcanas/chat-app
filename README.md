## Table of contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
This is a simple real-time chat app.
	
## Technologies
* [Laravel](https://laravel.com/)
* [InertiaJS](https://inertiajs.com/)
* [VueJS](https://vuejs.org/)
* [Soketi](https://soketi.app/)
* [Docker](https://www.docker.com/)
	
## Setup
Install dependencies

```
$ npm install
$ composer install
```
Start Sail

```
$ ./vendor/bin/sail up
```

Run migrations and seeders for test data

```
$ ./vendor/bin/sail artisan migrate --seed
```

Build project

```
$ ./vendor/bin/sail npm run build
```

Access project via localhost by default
```
http://localhost
```