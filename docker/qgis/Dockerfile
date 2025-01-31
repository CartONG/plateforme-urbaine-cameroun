# Dockerfile for QGIS Server
FROM qgis/qgis-server:ltr

ARG QGIS_SERVER_VERSION
ARG POSTGRES_HOST
ARG POSTGRES_PORT
ARG POSTGRES_DB
ARG POSTGRES_GEODATA_USER
ARG POSTGRES_GEODATA_PASSWORD

# Install envsubst
RUN apt-get update && apt-get install -y gettext && apt-get clean

# Create template file
COPY pg_service.conf.template /etc/postgresql-common/

# Generate the final pg_service.conf from template
RUN envsubst < /etc/postgresql-common/pg_service.conf.template > /etc/postgresql-common/pg_service.conf && \
    rm /etc/postgresql-common/pg_service.conf.template