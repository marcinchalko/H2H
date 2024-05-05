FROM php:8.2.0-fpm as builder

RUN apt-get update
RUN useradd -ms /bin/bash codekeeper

RUN mkdir -p /var/www/symfony
COPY ./app /var/www/symfony

WORKDIR /var/www/symfony

RUN apt-get update --fix-missing  \
    && apt-get install -y build-essential libldap2-dev zlib1g-dev libpq-dev git libicu-dev libxml2-dev libzip-dev libssl-dev libonig-dev wget nano procps acl libpng-dev wkhtmltopdf ccrypt graphviz zip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl ldap \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install zip xml opcache soap mbstring \
    && docker-php-ext-install -j$(nproc) gd

RUN touch /usr/local/etc/php/conf.d/uploads.ini \
    && echo "upload_max_filesize = 64M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 64M;" >> /usr/local/etc/php/conf.d/uploads.ini \
    # && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.max_accelerated_files=88000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.memory_consumption=512" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.max_wasted_percentage=50" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.interned_strings_buffer=64" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.save_comments=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.mmap_base=0x20000000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.file_cache_fallback=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.preload=/var/www/symfony/config/preload.php" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.preload_user=root" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "opcache.jit_buffer_size=512M" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # # && echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
    # && echo "memory_limit = 512M;" >> /usr/local/etc/php/conf.d/memory_limit.ini \
    && touch /usr/local/etc/php/conf.d/realpath.ini \ 
    && echo "realpath_cache_size = 4096;" >> /usr/local/etc/php/conf.d/realpath.ini \
    && echo "realpath_cache_ttl = 1200;" >> /usr/local/etc/php/conf.d/realpath.ini

RUN touch /var/log/cron.log

COPY ./docker/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \ 
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install cron
RUN apt-get install -y cron
# Configure cron
#RUN crontab -l | { cat; echo "0 13 * * * su -c '. /var/env; /usr/local/bin/php /var/www/symfony/bin/console app:delete:user >> /var/log/cron.log 2>&1'"; } | crontab -

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer

# Install and set supervisor
RUN apt-get install -y supervisor
ADD ./docker/php-fpm/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Openssl is the only required thing to install
RUN apt-get -y install openssl
    
RUN cp /etc/ssl/openssl.cnf /etc/ssl/openssl.cnf.ORI && \
    sed -i "s/\(MinProtocol *= *\).*/\1TLSv1.0 /" "/etc/ssl/openssl.cnf" && \
    sed -i "s/\(CipherString *= *\).*/\1DEFAULT@SECLEVEL=1 /" "/etc/ssl/openssl.cnf" && \
    (diff -u /etc/ssl/openssl.cnf.ORI /etc/ssl/openssl.cnf || exit 0)

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install apt-utils libxml2-dev gnupg apt-transport-https \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Warsaw /etc/localtime
RUN "date"

RUN find /var/www/symfony -type d -exec chmod 0775 "{}" \;
RUN find /var/www/symfony -type f -exec chmod 0664 "{}" \;
RUN find /var/www/symfony -type d -exec chmod g+s "{}" \;

ARG env
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer config --no-plugins allow-plugins.endroid/installer false \
    composer check-platform-reqs \
    && composer install \
        --prefer-dist \
        --optimize-autoloader \
        --no-interaction \
        --no-scripts
RUN composer dump-autoload 
    #--no-dev --classmap-authoritative

RUN chown codekeeper:codekeeper /var/www/symfony
USER codekeeper
WORKDIR /var/www/symfony

USER root

CMD php bin/console doctrine:migrations:migrate --no-interaction ; printenv | grep -v "no_proxy" >> /etc/environment ; /etc/init.d/cron start ; setfacl -dR -m u:www-data:rwX -m u:codekeeper:rwX var ; setfacl -R -m u:www-data:rwX -m u:codekeeper:rwX var ; supervisord

EXPOSE 9000
