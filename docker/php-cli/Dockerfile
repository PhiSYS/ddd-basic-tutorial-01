FROM php:8-cli

ENV LC_ALL="C.UTF-8"

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
        git \
        locales \
        unzip && \
    apt-get autoremove -y && \
    apt-get autoclean -y && \
    echo 'es_ES.UTF-8 UTF-8' >> /etc/locale.gen && \
    echo 'en_US.UTF-8 UTF-8' >> /etc/locale.gen  && \
    locale-gen

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN mkdir -p /usr/src/phisys/bin && \
	install-php-extensions \
		@composer \
		intl \
		opcache \
		zip \
		xdebug-^3.0

COPY docker/php-cli/config/php_dev.ini $PHP_INI_DIR/php.ini

WORKDIR /usr/src/phisys

ENV PATH /usr/src/phisys/bin:/usr/src/phisys/vendor/bin:$PATH
RUN chown -R www-data:www-data /usr/src/phisys
USER www-data
