FROM nginx:latest

COPY ./docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY ./app/public /var/www/symfony/public
#RUN chmod -R 775 /var/www/symfony/public/assets && chmod -R 775 /var/www/symfony/public/bundles && chmod -R 775 /var/www/symfony/public/build

COPY ./docker/nginx/docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod 775 ./docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]
