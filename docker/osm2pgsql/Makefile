create-geodata-user-schema:
#Create geodata user if it doesn't exist
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'DO $$$$ BEGIN IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = '\''${POSTGRES_GEODATA_USER}'\'') THEN CREATE ROLE ${POSTGRES_GEODATA_USER} LOGIN PASSWORD '\''${POSTGRES_GEODATA_PASSWORD}'\''; END IF; END $$$$;'

#Create geodata schema if it doesn't exist
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'DO $$$$ BEGIN IF NOT EXISTS (SELECT schema_name FROM information_schema.schemata WHERE schema_name = '\''${POSTGRES_GEODATA_SCHEMA}'\'') THEN CREATE SCHEMA ${POSTGRES_GEODATA_SCHEMA}; END IF; END $$$$;'

#Install required extensions
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'CREATE EXTENSION IF NOT EXISTS postgis;'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'CREATE EXTENSION IF NOT EXISTS hstore;'

#Set permissions on public schema (for PostGIS and hstore)
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT USAGE, CREATE ON SCHEMA public TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT SELECT, USAGE ON ALL SEQUENCES IN SCHEMA public TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT SELECT ON ALL TABLES IN SCHEMA public TO ${POSTGRES_GEODATA_USER};'

#Full permissions on geodata schema
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT ALL ON SCHEMA ${POSTGRES_GEODATA_SCHEMA} TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT ALL ON ALL TABLES IN SCHEMA ${POSTGRES_GEODATA_SCHEMA} TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'ALTER DEFAULT PRIVILEGES IN SCHEMA ${POSTGRES_GEODATA_SCHEMA} GRANT ALL ON TABLES TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT CREATE ON DATABASE ${POSTGRES_DB} TO ${POSTGRES_GEODATA_USER};'

#Configure search_path to include public and geodata schemas
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'ALTER ROLE ${POSTGRES_GEODATA_USER} SET search_path TO ${POSTGRES_GEODATA_SCHEMA}, public;'

	
DATA_DIR=./docker/osm2pgsql/data
OSM_FILE=cameroon-latest.osm.pbf
DOWNLOAD_URL=https://download.geofabrik.de/africa/$(OSM_FILE)

download-osm-file:
	mkdir -p $(DATA_DIR)
	wget -O $(DATA_DIR)/$(OSM_FILE) $(DOWNLOAD_URL)

init-osm-db:
	$(DOCKER_COMP) --profile osm up osm2pgsql
	rm $(DATA_DIR)/$(OSM_FILE)


create-osm-db: create-geodata-user-schema download-osm-file init-osm-db