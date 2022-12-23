# smalot/pdfparser demo

[Live demo](https://pdfparser.wrve.nl)

[smalot/pdfparser](https://github.com/smalot/pdfparser)  

## Installation

0. Have [docker](https://docs.docker.com/engine/install/) & [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) installed
1. Run `composer install --ignore-platform-reqs`
2. Run `cp .env.example .env`
3. Run `vendor/bin/sail up -d`
4. Run `vendor/bin/sail npm install`
5. Run `vendor/bin/sail npm run dev`
6. Run `php artisan key:generate`
7. Go to `localhost`
