FROM php:5.5-apache 
RUN apt-get update 
RUN apt-get install -y php5-mysql && rm -f /etc/apache2/mods-available/php5.load
RUN apt-get install -y php5-gd
RUN apt-get install -y libapache2-mod-php5
RUN a2enmod rewrite
RUN a2enmod php5

RUN rm -f /etc/apache2/conf-enabled/docker-php.conf
COPY conf/apache/php.conf /etc/apache2/conf-enabled/php.conf

