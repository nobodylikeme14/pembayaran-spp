<p align="center"><a href=""><img src="https://res.cloudinary.com/ddke6cwho/image/upload/v1692584763/educashlog-logo.png" width="400"></a></p>

# EduCashLog Website

## Requirements

* PHP 7.4.7 or higher
* Composer
* MySQL

## Installation

1. Clone the project to your local machine.
`git clone https://github.com/nobodylikeme14/pembayaran-spp.git`

2. Run `composer install` to install the dependencies.
3. Create a `.env` file and configure the database settings.

4. Run the following command to generate a unique application key:
`php artisan key:generate`

5. Create a new database for the project on your local MySQL server. Update the database name, username, and password in the `.env` file. Run `php artisan migrate` to set up the database tables.



6. Run `php artisan serve` to start the development server.


## Usage

The project will be available at `http://localhost:8000`.