
## 
## --------------------------------------------------------------------------------
##  🎵  Symfony commands
## --------------------------------------------------------------------------------

sf:			## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc:			## Clear the cache
	@$(SYMFONY) c:c

init-jwt-keypair:	## Generate a new JWT keypair
	@$(SYMFONY) lexik:jwt:generate-keypair --overwrite

## 
## --------------------------------------------------------------------------------
##  🧙  Composer
## --------------------------------------------------------------------------------

composer:		## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

composer-install:	## Run composer install
	@$(COMPOSER) install

composer-update:	## Run composer update
	@$(COMPOSER) update

composer-dump-env:	## Run composer dump-env
ifdef APP_ENV
	@$(COMPOSER) dump-env ${APP_ENV}:
else
	@$(COMPOSER) dump-env dev:
endif

vendor:			## Install vendors according to the current composer.lock file
	@$(COMPOSER) install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction


## 
## --------------------------------------------------------------------------------
##  🛢️  Database - Doctrine
## --------------------------------------------------------------------------------

recreate-database: restart-db drop-database create-database configure-database	## Recreate the database

drop-database:		## Drop the database
	@$(SYMFONY) do:da:dr -f --if-exists

create-database:	## Create the database
	@$(SYMFONY) do:da:cr --if-not-exists

run-migration:		## Run migrations
	@$(SYMFONY) do:mi:mi -n --allow-no-migration

run-fixtures:		## Run fixtures
	@$(SYMFONY) ha:fi:lo -n -vvv

make-migration:		## Create a new migration
	@$(SYMFONY) make:migration

make-entity:		## Create a new entity
	@$(SYMFONY) make:entity

regenerate-entity:	## Regenerate entities
	@$(SYMFONY) make:entity --regenerate

enable-postgis:		## Enable postgis
	@$(SYMFONY) dbal:run-sql 'CREATE extension postgis;'

make-run-migration: make-migration run-migration	## Create and run a new migration

reset-database: recreate-database run-migration		## Reset the database (recreate and run migrations)

rerun-database: reset-database run-fixtures		## Reset the database (recreate, run migrations and run fixtures)

configure-database: enable-postgis	## Configure the database


create-short-migration: reset-database make-migration run-migration run-fixtures ## Create a small migration

update-prod-database: composer-install composer-dump-env cc run-migration	## Update database without clearing data

## 
## --------------------------------------------------------------------------------
##  🛠️  Tools
## --------------------------------------------------------------------------------

run-php-cs-fixer:	## Run php-cs-fixer
	@$(DOCKER_EXEC_PHP) php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
