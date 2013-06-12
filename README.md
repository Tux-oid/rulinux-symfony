RuLinux engine
=======

##Requirements

* apache
* php
* postgresql
* cron
* latex

##Installation

Run:

* composer.phar install
* php app/console doctrine:database:create
* php app/console doctrine:schema:create
* php app/console doctrine:fixtures:load
* php app/console assets:install web --symlink
* php app/console assetic:dump

