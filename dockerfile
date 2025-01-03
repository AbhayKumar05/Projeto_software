# Backend Dockerfile
FROM php:8.1-apache
COPY ./src /var/www/html
RUN docker-php-ext-install mysqli
EXPOSE 80
ADD https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /wait-for-it.sh
RUN chmod +x /wait-for-it.sh