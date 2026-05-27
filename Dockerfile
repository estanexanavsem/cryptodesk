FROM php:8.3-cli

WORKDIR /app
COPY . /app

# The SQLite database is created at runtime by the app; keep its dir writable.
RUN mkdir -p /app/data && chmod -R 0777 /app/data

# Render injects $PORT at runtime; this default is for local `docker run`.
ENV PORT=10000
EXPOSE 10000

# php:8.3-cli ships with pdo_sqlite + sqlite3, so no extra extensions needed.
# index.php doubles as the built-in server's router (serves assets, routes the rest).
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public public/index.php"]
