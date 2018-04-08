#!/usr/bin/env bash

./vendor/bin/phpunit

composer install

php -S localhost:8100 -t ./