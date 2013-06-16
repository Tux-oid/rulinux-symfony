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
* php app/console rl_main:generate:users:filters
* php app/console clear:cache
* ln -s vendor/rl/ucaptcha/RL/Ucaptcha/cpt/ web/cpt
* sudo setfacl -dR -m u:wwwrun:rwX -m u:`whoami`:rwX vendor/rl/ucaptcha/RL/Ucaptcha/cpt/
* sudo setfacl -R -m u:wwwrun:rwX -m u:`whoami`:rwX vendor/rl/ucaptcha/RL/Ucaptcha/cpt/


Add to crontab the following lines

    * * * * * php app/console rl_main:messages:filter >> /dev/null
    * * * * * php app/console rl_main:generate:users:filters >> /dev/null
