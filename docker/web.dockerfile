FROM nginx:stable

ADD config/vhost.conf /etc/nginx/conf.d/default.conf