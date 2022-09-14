DOCKER_FOLDER  = cd docker;
DOCKER_COMPOSE = $(DOCKER_FOLDER) docker-compose
EXEC_PHP       = $(DOCKER_COMPOSE) exec php-fpm
DOCKER_COMPOSE_FILE = -f docker-compose.yml

local-build: local-compose-file dc-build init-local
init-local: dc-down dc-up

dc-build:
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FILE) build  php-fpm

dc-up:
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FILE) up -d  php-fpm

dc-down:
	$(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FILE) down --remove-orphans

bash:
	$(EXEC_PHP) bash

