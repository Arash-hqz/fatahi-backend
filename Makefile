## Makefile - helper targets to run Laravel DB commands inside Docker `app` container
## Usage examples:
##   make db-migrate           # runs migrations
##   make db-rollback          # rolls back last batch
##   make db-fresh             # migrate:fresh and seed
##   make db-seed              # run db:seed
## Options:
##   USE_EXEC=true             # use `docker-compose exec` instead of `run`
##   ARGS="--step=1"         # additional args passed to artisan

.PHONY: db-migrate db-rollback db-fresh db-seed artisan
 .PHONY: db-refresh
 .PHONY: test

ARGS ?=
USE_EXEC ?= false

DB_COMPOSE = docker-compose run --rm --no-deps app
DB_EXEC = docker-compose exec -T app

ifeq ($(USE_EXEC),true)
RUN_CMD := $(DB_EXEC)
else
RUN_CMD := $(DB_COMPOSE)
endif

db-migrate:
	@echo "Running migrations inside app container..."
	$(RUN_CMD) php artisan migrate --force $(ARGS)

db-rollback:
	@echo "Rolling back last migration inside app container..."
	$(RUN_CMD) php artisan migrate:rollback --force $(ARGS)

db-fresh:
	@echo "Refreshing database (migrate:fresh) inside app container..."
	$(RUN_CMD) php artisan migrate:fresh --seed --force $(ARGS)

db-seed:
	@echo "Seeding database inside app container..."
	$(RUN_CMD) php artisan db:seed --force $(ARGS)

db-refresh:
	@echo "Running migrate:fresh then db:seed inside app container..."
	$(RUN_CMD) php artisan migrate:fresh --force $(ARGS)
	$(RUN_CMD) php artisan db:seed --force $(ARGS)

artisan:
	@echo "Running artisan $(ARGS) inside app container..."
	$(RUN_CMD) php artisan $(ARGS)

test:
	@echo "Running phpunit tests inside app container..."
	$(RUN_CMD) ./vendor/bin/phpunit --testdox
