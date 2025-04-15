FROM postgis/postgis:14-3.2

RUN apt-get update && apt-get install -y libsqlite3-dev curl build-essential postgresql-server-dev-14

RUN curl -L -o /tmp/sqlite_fdw.tar.gz https://github.com/pgspider/sqlite_fdw/archive/refs/tags/v2.1.1.tar.gz \
    && tar xfz /tmp/sqlite_fdw.tar.gz \
    && rm -r /tmp/sqlite_fdw.tar.gz \
    && cd sqlite_fdw-2.1.1 \
    && make USE_PGXS=1 && make install USE_PGXS=1 \
    && rm -rf /tmp/sqlite_fdw-2.1.1

VOLUME /var/shared
RUN mkdir -p /var/shared && chmod 0777 /var/shared
