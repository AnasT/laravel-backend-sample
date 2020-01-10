ARG NGINX_VERSION=1.17
FROM nginx:$NGINX_VERSION

COPY ./nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www
