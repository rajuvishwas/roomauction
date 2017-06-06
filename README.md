<h1>Room Auction</h1>

<p>
<a href="https://travis-ci.org/rajuvishwas/roomauction"><img src="https://travis-ci.org/rajuvishwas/roomauction.svg?branch=master" alt="Build Status" /></a>
</p>

## Server Requirement

* PHP >= 5.6.4
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

## Installation

We need <a href="https://getcomposer.org/">Composer</a> to manage our dependencies, so make sure you have Composer installed in your machine. Once installed, please follow the below steps:

### Setup Project

* `git clone https://github.com/rajuvishwas/roomauction.git roomauction` to clone repository

* `cd roomauction`

* `composer install` to install all dependencies

* `composer update`

* `cp .env.example .env`

* `php artisan key:generate` to regenerate secure key for your environment

### Setup Database

If you are using MySQL, create a database and update the below configuration in `.env` file:

* DB_CONNECTION=mysql

* DB_DATABASE

* DB_USERNAME

* DB_PASSWORD

If you are using sqlite, then follow the below steps:

* `touch database/database.sqlite` to create a sqlite file in database folder

* `DB_CONNECTION=sqlite` to change connection to sqlite

### Setup Configurations

Below are configuration which you can change for your applications:

* `APP_ADMIN` - An administrator user will be created with the specified email address

* `APP_RESULTS_PER_PAGE` - Lists of results to be displayed on page when using pagination

* `APP_ENCODED_PAGINATOR` - Page number will be encoded when using pagination

* `APP_AUCTION_EXPIRES` - Auction expiry time in minutes

* `APP_CURRENCY_SYMBOL` - Currency symbol for your application

* `APP_BID_ACCEPTED_PERCENT` - Set acceptance percentage for bid

* `APP_BID_LASTMINUTE_EXTEND` - Set extended time for last minute bid in minutes

### Migration

To setup database and seed tables with default values, run the below step:

* `php artisan migrate --seed`

## Run

### Run on Browser
To run the application on browser, run the below command:

* `php artisan serve`

We send a notification to Partners API, when their bid is no longer winner. We use Laravel Queues for this which help us to delegate the API call to their server. To start the queue worker, use the below command:

* `php artisan queue:work`

You can use the below details to login:
* Email Address: `admin@roomsquickly.com` or value set on APP_ADMIN in .env file
* Password: `admin`

### Run Test

We use PHPUnit for our testing, so run the below command:

* `vendor\bin\phpunit`
