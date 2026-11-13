FROM dunglas/frankenphp:1-php8.5-alpine

# Установка системных зависимостей, Node.js/NPM для фронтенда и расширений PHP
RUN apk add --no-cache bash nodejs npm \
    && install-php-extensions pdo_pgsql pgsql zip intl opcache bcmath

# Копируем Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Сначала копируем только файлы зависимостей (для кэширования слоев Docker)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-scripts

COPY package.json package-lock.json* ./
RUN npm install

# Теперь копируем остальной исходный код
COPY . /var/www/html

# Завершаем скрипты Composer, собираем фронтенд и удаляем node_modules для экономии места
RUN composer dump-autoload --optimize \
    && npm run build \
    && rm -rf node_modules

# Настройка прав для Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
EXPOSE 443
