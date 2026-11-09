FROM dunglas/frankenphp:1-php8.5-alpine

# Установка системных зависимостей и расширений PHP для PostgreSQL
# (FrankenPHP поставляется со встроенным установщиком расширений docker-php-ext-install)
RUN apk add --no-cache bash \
    && install-php-extensions pdo_pgsql pgsql zip intl opcache bcmath

# Копируем исходный код проекта в контейнер
COPY . /var/www/html

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Настройка прав для Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Выставляем порты (FrankenPHP слушает 80 и 443 для SSL автоматически)
EXPOSE 80
EXPOSE 443
