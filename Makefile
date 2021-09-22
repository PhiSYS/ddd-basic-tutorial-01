include .env
export

UID=$(shell id -u)
GID=$(shell id -g)
DOCKER_PHP_SERVICE=php-cli

export UID := ${UID}

build: erase cache-folders build-docker composer-install

up:
	docker-compose up -d

stop:
	docker-compose stop

shell:
	docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bash

logs:
	docker-compose logs -f ${DOCKER_PHP_SERVICE}

erase:
	-docker-compose down -v --remove-orphans
	-truncate -s 0 ./var/log/*.log

prune: erase
	docker system prune --volumes
	docker image prune --all

cache-folders:
	-mkdir -p ~/.composer
	-sudo chown -R ${UID}:${GID} ~/.composer
	-mkdir -p var/cache var/log
	-sudo chown -R ${UID}:${GID} var/cache var/log && sudo chmod -R ug+s var/cache

build-docker:
	-docker pull mlocati/php-extension-installer
	docker-compose build --build-arg UID=${UID} --build-arg GID=${GID}  && \
	docker-compose pull

cache-warmup:
	docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} console cache:warmup

composer-install:
	docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} /usr/local/bin/composer install -o

unit-tests:
	docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} phpunit
