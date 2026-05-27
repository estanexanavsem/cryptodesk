FROM php:8.3-apache

# Front-controller routing via public/.htaccess needs mod_rewrite.
# Apache (unlike `php -S`) serves assets and handles concurrent requests properly.
RUN a2enmod rewrite

WORKDIR /app
COPY . /app

# Seed the SQLite database at build time so the image ships ready-to-serve.
# This avoids a first-request seeding race when the container cold-starts under
# concurrent traffic (e.g. when Render wakes the free instance from sleep).
RUN mkdir -p /app/data && php bin/seed.php && chmod -R 0777 /app/data

# Serve from public/ with .htaccess overrides enabled.
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# php:8.3-apache ships with pdo_sqlite + sqlite3, so no extra extensions needed.
ENV PORT=10000
EXPOSE 10000

# Apache must listen on the port Render injects via $PORT.
CMD ["sh", "-c", "sed -ri \"s/^Listen 80$/Listen ${PORT}/\" /etc/apache2/ports.conf && sed -i \"s/__PORT__/${PORT}/\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
