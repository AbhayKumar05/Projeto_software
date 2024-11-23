# Frontend Dockerfile
FROM nginx:latest
COPY ./frontend /usr/share/nginx/html
EXPOSE 80

# Backend Dockerfile
FROM php:8.1-apache
COPY ./backend /var/www/html
RUN docker-php-ext-install mysqli
EXPOSE 80

# Reverse Proxy Dockerfile
FROM nginx:latest
COPY ./nginx.conf /etc/nginx/nginx.conf
EXPOSE 80
