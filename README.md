# Project setup

The project is build on Laravel 6, and it utilizes [Homestead](https://laravel.com/docs/6.x/homestead). A basic knowledge of [Vagrant](https://www.vagrantup.com/) is required. 

After cloning the repository, you'll first have to install dependencies: 

    $ composer install
    
and then bring up Homestead with: 
       
    $ vagrant up

connect to vm's terminal with:

    $ vagrant ssh

and run every command inside the vm

You'll find Homestead's configuration in `./Homestead.yaml`

In order to be able to make requests to api you need to run

    $ php artisan passport:install --uuids

Read more in `https://laravel.com/docs/7.x/passport`

You will also have to update your `hosts` file to point the domain `backend.test` to the virtual machines's IP:

    192.168.10.10 backend.test

You can make a symlink of phpmyadmin in public folder or download it from `https://github.com/phpmyadmin/phpmyadmin`

    http://backend.test/phpmyadmin

For better ide intellisense run the following commands:

    $ php artisan ide-helper:generate
    $ php artisan ide-helper:meta
    $ php artisan ide-helper:models --nowrite
    
Finally, you'll need a `.env` for local configuration. Here's an example: 

    APP_NAME=Backend
    APP_ENV=local
    APP_KEY=base64:2KYeZAJ7htSIbHLk4GBOQWUHZRYMnM4yjmk8ULcCvvc=
    APP_DEBUG=true
    APP_URL=http://backend.test
    
    LOG_CHANNEL=stack
    
    DB_CONNECTION=mysql
    DB_HOST=192.168.10.10
    DB_PORT=3306
    DB_DATABASE=carrental
    DB_USERNAME=homestead
    DB_PASSWORD=secret
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1
    
    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Database setup

To run the database migrations, use: 

    ./artisan migrate
    
To refresh all database migrations (which will erase all data), use:

    ./artisan migrate:refresh
