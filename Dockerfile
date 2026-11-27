FROM dunglas/frankenphp:1-php8.5-alpine

# 1. Базовые утилиты и тяжёлые PHP-расширения (редко меняются, кэшируются надолго)
RUN apk add --no-cache bash nodejs npm \
    && install-php-extensions pdo_pgsql pgsql bcmath intl sodium zip

# 2. Дополнительные расширения отдельным слоем (чтобы не пересобирать intl при правках)
RUN install-php-extensions sockets

# Копируем Composer и разрешаем запуск от root без предупреждений
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

# 3. Устанавливаем PHP-зависимости с кэшем BuildKit
COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install --no-interaction --optimize-autoloader --no-scripts

# 4. Устанавливаем JS-зависимости быстрее через npm ci и кэш
COPY package.json package-lock.json* ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci --no-audit --no-fund

# 5. Копируем исходный код
COPY . /var/www/html

# 6. Генерируем автозагрузчик, собираем фронтенд и чистим node_modules
RUN composer dump-autoload --optimize \
    && npm run build \
    && rm -rf node_modules

# Настройка прав для Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
EXPOSE 443
