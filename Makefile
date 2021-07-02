.PHONY: up
up:
	@docker-compose up -d
	symfony server:start -d

.PHONY: down
down:
	@symfony server:stop
	@docker-compose down

.PHONY: analyze
analyze:
	@codeclimate analyze src

.PHONY: ecs-check
ecs-check:
	@vendor/bin/ecs check src/

.PHONY: ecs-fix
ecs-fix:
	@vendor/bin/ecs check src/ --fix
