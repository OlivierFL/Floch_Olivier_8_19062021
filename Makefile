.PHONY: up
up:
	@docker-compose up -d
	symfony server:start -d

.PHONY: down
down:
	@symfony server:stop
	@docker-compose down

.PHONY: install
install:
	@composer install
	symfony console doctrine:database:create
	symfony console doctrine:schema:create
	symfony console doctrine:fixtures:load -n

.PHONY: db-reset
db-reset:
	symfony console doctrine:database:drop --force || true
	symfony console doctrine:database:create
	symfony console doctrine:schema:create
	symfony console doctrine:fixtures:load -n

.PHONY: tests-db-reset
tests-db-reset:
	APP_ENV=test symfony console doctrine:database:drop --force || true
	APP_ENV=test symfony console doctrine:database:create
	APP_ENV=test symfony console doctrine:schema:create
	APP_ENV=test symfony console doctrine:fixtures:load -n

.PHONY: tests
tests:
	make tests-db-reset
	APP_ENV=test symfony php bin/phpunit --colors

.PHONY: tests-no-reset
tests-no-reset:
	APP_ENV=test symfony php bin/phpunit --colors

.PHONY: tests-entity
tests-entity:
	make tests-db-reset
	APP_ENV=test symfony php bin/phpunit tests/Entity --colors

.PHONY: tests-entity-no-reset
tests-entity-no-reset:
	APP_ENV=test symfony php bin/phpunit tests/Entity --colors

.PHONY: tests-functional
tests-functional:
	make tests-db-reset
	APP_ENV=test symfony php bin/phpunit tests/Controller --colors

.PHONY: tests-functional-no-reset
tests-functional-no-reset:
	APP_ENV=test symfony php bin/phpunit tests/Controller --colors

.PHONY: tests-coverage
tests-coverage:
	make tests-db-reset
	APP_ENV=test symfony php bin/phpunit --colors --coverage-html tests-coverage

.PHONY: tests-coverage-no-reset
tests-coverage-no-reset:
	APP_ENV=test symfony php bin/phpunit --colors --coverage-html tests-coverage

.PHONY: analyze
analyze:
	@codeclimate analyze src

.PHONY: ecs-check
ecs-check:
	@vendor/bin/ecs check src/

.PHONY: ecs-fix
ecs-fix:
	@vendor/bin/ecs check src/ --fix
