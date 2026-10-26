FROM php:8.2-alpine

# Pre-compiled binary extension installer (instant, no compile)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql

WORKDIR /var/www/html
COPY . .

EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
