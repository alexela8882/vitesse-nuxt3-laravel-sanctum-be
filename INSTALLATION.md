# INSTALLATION GUIDE

### PRE-REQUISITES

1. NodeJS 16*
2. Php 7.3+
3. Composer
4. Nginx
5. MariaDB 10/MySQL
6. Git

### BACKEND

**Setting your project**

1. Open terminal then type `cd /var/www/html`
2. Clone repository:

 ```
 sudo git clone https://github.com/alexela8882/vitesse-nuxt3-laravel-sanctum-be.git be.escophotos.es
 ```
3. `cd be.escophotos.es`
4. `sudo chmod -R 777 .`
5. `composer install`
6. `sudo cp .env.development .env`
7. Configure your `.env` file for database connection
8. Change `APP_URL` value into `http://be.escophotos.es`
9. Change `SANCTUM_STATEFUL_DOMAINS` value into `escophotos.es,frontend.escophotos.es,escoaster.escophotos.es,escolifesciences.escophotos.es,evxventures.escophotos.es`
10. Change `SESSION_DOMAIN` value into `.escophotos.es`
11. `php artisan key:generate`
12. `php artisan migrate`
13. Go to **database/seeders/DatabaseSeeder.php** and uncomment all codes under `run` method
14. `php artisan db:seed`


### FRONTEND

1. Open terminal then type `cd /var/www/html`
2. Clode repository:
```
sudo git clone https://github.com/alexela8882/vitesse-nuxt3-laravel-sanctum-fe.git fe.escophotos.es
```
3. `cd fe.escophotos.es`
4. `sudo chmod -R 777 .`
5. `sudo pnpm install`

### SYSTEM CONFIGURATION

**Configure `hosts` file and `nginx`**

1. `sudo nano /etc/hosts`
2. Add these lines:
```
127.0.0.1	escophotos.es
127.0.0.1	www.escophotos.es
127.0.0.1	frontend.escophotos.es
127.0.0.1	escoaster.escophotos.es
127.0.0.1	escolifesciences.escophotos.es
127.0.0.1	evxventures.escophotos.es
127.0.0.1	backend.escophotos.es
```
3. `cd /etc/nginx/sites-enables/`
4. To make backend configuration 
```
sudo mkdir be.escophotos.es.conf
```
5. Add this code:
```
server {
    listen 80;
    server_name be.escophotos.es;
    root /var/www/html/be.escophotos.com/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
6. To make frontend configuration
```
sudo mkdir fe.escophotos.es.conf
```
7. Add this code:
```
server {
    listen 80;
    server_name escophotos.es frontend.escophotos.es escoaster.escophotos.es escolifesciences.escophotos.es evxventures.escophotos.es;

    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### STARTING THE APP

**BACKEND**

1. Open browser then navigate `be.escophotos.es`

**FRONTEND**

1. For development, open terminal then
```
cd /var/www/html/fe.escophotos.es
sudo pnpm dev

// note: you should see the app running in 127.0.0.1:3000 or any port you may want for your setup
```
2. For production. configure the `ecosystem.config.js` file then
```
sudo pnpm build
pm2 start

// if you don't have pm2 installed
use `npm install pm2`
```
3. Open browser then navigate `fe.escophotos.es`

### ALL DONE!!!