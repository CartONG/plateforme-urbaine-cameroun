FROM ubuntu:latest

# Args for imposm configuration file
ARG POSTGRES_GEODATA_USER
ARG POSTGRES_GEODATA_PASSWORD
ARG POSTGRES_DB
ARG POSTGRES_HOST

ENV POSTGRES_GEODATA_USER=${POSTGRES_GEODATA_USER}
ENV POSTGRES_GEODATA_PASSWORD=${POSTGRES_GEODATA_PASSWORD}
ENV POSTGRES_DB=${POSTGRES_DB}
ENV POSTGRES_HOST=${POSTGRES_HOST}

# Install wget & envsubst
RUN apt-get update && apt-get install -y gettext && apt-get clean

# Install Imposm
RUN curl -L https://github.com/omniscale/imposm3/releases/download/v0.14.0/imposm-0.14.0-linux-x86-64.tar.gz -o /tmp/imposm.tar.gz && \
    tar -xzf /tmp/imposm.tar.gz -C /usr/local/bin --strip-components=1 && \
    rm /tmp/imposm.tar.gz

# Create Imposm folders
RUN mkdir -p /srv/imposm/data /srv/imposm/config /srv/imposm/logs /srv/imposm/imposm_cache /srv/imposm/imposm_diff /srv/imposm/scripts

# Copy Imposm files
COPY /files/*.json /files/*.yaml /files/*.geojson /srv/imposm/config/

# Replace placeholders in Imposm config file
RUN envsubst < /srv/imposm/config/config.template.json > /srv/imposm/config/config.json && \
    rm /srv/imposm/config/config.template.json

# Download OSM PBF
RUN curl -L https://download.geofabrik.de/africa/cameroon-latest.osm.pbf -o /srv/imposm/data/cameroon-latest.osm.pbf

# Keep container active
CMD ["tail", "-f", "/dev/null"]
