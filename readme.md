### Config
All the project configurations are in the **.env** file. See below:

```
APP_ENV=local
APP_DEBUG=true
APP_KEY=SomeRandomKey!!!

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=c1
DB_USERNAME=root
DB_PASSWORD=rootpass

CACHE_DRIVER=array
QUEUE_DRIVER=array
```

nginx host file:
```
server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    root /vagrant/note/public;
    index index.php index.html index.htm;

    server_name 192.168.0.105;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```
Once the database is setup, execute

**php artisan migrate**

### Test account setup
Please execute the following MySQL query: 
insert into users (name, email, password) 
values ('test', 'test@test.com', '$2y$10$R3LHeF6SA8x9RCYMlzkDOOj1pifjIQS/jab2M7t/kBe3W8X1MRGy2')

### Vagrant
The project includes a vagrant file for ubuntu/trusty64. Follow this to get php/nginx setup:
https://www.digitalocean.com/community/tutorials/how-to-install-laravel-with-an-nginx-web-server-on-ubuntu-14-04
