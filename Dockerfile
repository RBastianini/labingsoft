FROM php:8.3-apache AS base_image
# Update package lists and install common composer tools
RUN apt-get update && apt-get install wget unzip git -y
# Install docker-php-extension-installer
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install base PHP extensions
RUN install-php-extensions \
    @composer \
    xdebug \
    pdo_pgsql \
    intl 

ARG UID
ARG GUID
ENV UID=$UID
ENV GUID=$GUID
# Hack: Change www-data uid/group to allow apache to read files we will create.
# Change www-data home folder to avoid placing config / cache files in /var/www.
#RUN sed -ri "s!^www-data:x:33:33:www-data:/var/www!www-data:x:$UID:$GUID:www-data:/home/www-data!" /etc/passwd
RUN deluser www-data && groupadd -g $GUID www-data && useradd -s /bin/bash -m -d /home/www-data -g $GUID -r -u $UID www-data

# Change document root to /var/www/public
RUN sed -ri -e 's!/var/www/html!/var/www/files/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/files/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN echo "<FilesMatch \\.php\$> \n\
	SetHandler application/x-httpd-php \n\
</FilesMatch> \n\
DirectoryIndex disabled \n\
DirectoryIndex index.php index.html \n\
<Directory /var/www/files/public> \n\
	Options -Indexes \n\
	AllowOverride All \n\
	Order Allow,Deny \n\
        Allow from All \n\
        FallbackResource /index.php \n\
</Directory>" > /etc/apache2/conf-available/docker-php.conf
RUN echo "xdebug.mode=debug \nxdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# Create the www-data user home folder and change owner to it
RUN mkdir -p /home/www-data && chown www-data /home/www-data
# Use the default development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/files
