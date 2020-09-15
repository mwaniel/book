
### Installation

```bash
$ cd book system
$ npm install 
$ composer install
```

```bash
    first rename .env-example file to .env
    add database credentials, after that add for Email Queuing
    QUEUE_CONNECTION=database
```


### After that generate Key for application
```sh
php artisan key:generate
```
###  migrate
```sh
 php artisan migrate
```
## As we are Using Passport Install it Client
```sh
 php artisan passport:install
```
### Now, Our Application Ready To Run. All We need To Do Just Run Command Below Given
```sh
php artisan serve
127.0.0.1:8000
```
### For Vue js Run Command
```sh
  npm run watch
```
### As We Are Using Mail Queue
```sh
    php artisan queue:listen
```
Now Website is Running 

