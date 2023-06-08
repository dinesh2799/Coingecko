Instructions for running the project

composer install 

Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration.

Run the below commands

php artisan key:generate

php artisan migrate

Now run the below command

php artisan coin:fetch 

Check in the database whether the data is stored or not.

There is no need to for importing database as I am using migrations but still I kept a file named coingecko.sql in the main directory. Don't import.


The main file is located in app/Console/Commands/FetchCoingeckoData.php
