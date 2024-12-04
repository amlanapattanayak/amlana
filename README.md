# Stats viewing app

Create a basic stats viewing application by designing a database schema,
importing data from a CSV file and implementing the routes.

## Installation

This project comes with a pre-seeded SQLite database. Run `php artisan serve` to
start a web server.

Make sure to run the migrations and seeders and update the configuration if you
would like to run this application with a MySQL database.

#### - Candidate - Amlana - Assignment - Installation - Steps
A. Step to pull the code and create database
Step 1:
cd <project location>

Step 2:
git clone https://github.com/amlanapattanayak/ascendeum-monetization.git ./

Step 3:
Create MySQL database "ascendeum-monetization"

Step 4: 
Change the credentials in .env around line #22

B. Step to setup the Laravel assignment project
Step 1:
Run the following command
composer install

Step 2: 
php artisan key:generate

Step 3:
php artisan cache:clear

Step 4:
php artisan migrate

Step 5:
To import CSV for Campaign Monetisation Event conent, use the following command 
php artisan app:import-stats storage/stats_2024_03_31.csv

Step 5:
php artisan serve

Open the URL 
http://127.0.0.1:8000/campaigns/revenue

Following new routes are created

Route::get('/campaigns/revenue', [MonetizationEventController::class, 'showAggregatedRevenue']);
Route::get('/campaigns/revenue/{campaign}', [MonetizationEventController::class, 'showAggregatedRevenueByTime']);
Route::get('/campaigns/revenue/{campaign}/{datetime}', [MonetizationEventController::class, 'showAggregatedRevenueByTimeTerm']);

## Assignment

In the `storage/` folder you will find two files: _stats_2024_03_31.csv_ and
_stats_2024_04_01.csv_.

These files contain individual monetization events with or without tracking
parameters: utm_campaign and utm_term.

First, extend the database schema in a way that lets you store these stats in a
format which allows you run hourly breakdowns by utm_campaign and utm_campaign +
utm_term. Stats should link to the campaigns table.

The schema should be as normalized as possible, preferably 3rd normal form.
utm_campaign and utm_term values should exist only once in the whole database.

Second, implement the `ImportStatsCommand`. It should accept a filename and import
the data from that file into the newly created schema. A row should not be
imported when it does not have a value for the utm_campaign or utm_term column.

Finally, implement the routes defined in `routes/web.php`. The first route
should render a table with all revenue aggregated by campaign. Each row should
link to the second route.

The second route should render a table with all revenue for a single campaign
broken down by date and hour.

The third route should render a table with all revenue for a single campaign
broken down by utm_term.
