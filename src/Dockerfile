# Frontend Dockerfile
FROM nginx:latest
COPY ./src /usr/share/nginx/html
EXPOSE 80

# Backend Dockerfile
FROM php:8.1-apache
COPY ./src /var/www/html
RUN docker-php-ext-install mysqli
EXPOSE 80

# Reverse Proxy Dockerfile
FROM nginx:latest
#COPY ./src/nginx.conf /etc/nginx/nginx.conf
EXPOSE 80
