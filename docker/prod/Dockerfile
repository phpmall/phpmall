FROM hyperf/hyperf:8.2-alpine-vedge-swoole
LABEL maintainer="Juling Developers <team@julingyun.cn>" version="1.0" license="Apache-2.0" app.name="PHPMall"

ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=production \
    APP_DEBUG=(false)

# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php* \
    # - config PHP
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee conf.d/99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /opt/www

COPY . /opt/www

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories
RUN apk update && apk upgrade
RUN composer config -g repos.packagist composer https://packagist.pages.dev
RUN composer install --no-dev -oW
RUN php artisan optimize

EXPOSE 8000

ENTRYPOINT ["php", "artisan", "octane:start", "--host", "0.0.0.0"]
