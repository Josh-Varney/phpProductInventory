# CakePHP Application Skeleton

![Build Status](https://github.com/cakephp/app/actions/workflows/ci.yml/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

A skeleton for creating applications with [CakePHP](https://cakephp.org) 5.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist cakephp/app myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration

Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

## Layout

The app skeleton uses [Milligram](https://milligram.io/) (v1.3) minimalist CSS
framework by default. You can, however, replace it with any other library or
custom styles.

## Product 
Requirements:
Add validation rules:
name: Required, unique, must be between 3 and 50 characters.
quantity: Integer, >= 0, <= 1000.
price: Decimal, > 0, <= 10,000.
Custom validation:
Products with a price > 100 must have a minimum quantity of 10.
Products with a name containing "promo" must have a price < 50.
Controllers:
ProductsController with:
index: List all products with filters for status (in stock, low stock, out of stock) and a search box for names.
add: Add a new product.
edit: Update product details.
delete: Soft-delete products (set a deleted flag instead of removing them from the database).
Views:
Product list page with:
Columns: name, quantity, price, status, last updated.
Search box for partial name matches.
Pagination
A form to add/edit products.
A confirmation modal before deleting a product. 
Additional Requirements:
Calculate the status dynamically based on quantity:
in stock: Quantity > 10.
low stock: Quantity between 1 and 10.
out of stock: Quantity = 0.
Implement a behaviour to automatically update the last_updated field whenever a product is modified. 
Implement a unit test suite for the Product model and controller actions.
Seed the database with 10 sample products, ensuring variety in status, price, and quantity.
Ensure this follows CakePHP coding standards: https://book.cakephp.org/5/en/contributing/cakephp-coding-conventions.html