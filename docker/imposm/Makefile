create-geodata-user-schema:
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'DO $$$$ BEGIN IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = '\''${POSTGRES_GEODATA_USER}'\'') THEN CREATE ROLE ${POSTGRES_GEODATA_USER} LOGIN PASSWORD '\''${POSTGRES_GEODATA_PASSWORD}'\''; END IF; END $$$$;'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'DO $$$$ BEGIN IF NOT EXISTS (SELECT schema_name FROM information_schema.schemata WHERE schema_name = '\''${POSTGRES_GEODATA_SCHEMA}'\'') THEN CREATE SCHEMA ${POSTGRES_GEODATA_SCHEMA} AUTHORIZATION ${POSTGRES_GEODATA_USER}; END IF; END $$$$;'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT ALL ON SCHEMA ${POSTGRES_GEODATA_SCHEMA} TO ${POSTGRES_GEODATA_USER};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'ALTER ROLE ${POSTGRES_GEODATA_USER} SET search_path TO ${POSTGRES_GEODATA_SCHEMA};'
	@$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'GRANT CREATE ON DATABASE ${POSTGRES_DB} TO ${POSTGRES_GEODATA_USER};'
	# @$(POSTGRES) psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} -c 'REVOKE USAGE ON SCHEMA public FROM ${POSTGRES_GEODATA_USER};'

init-osm-db:
	docker compose exec imposm imposm import -config "/srv/imposm/config/config.json" -read "/srv/imposm/data/cameroon-latest.osm.pbf" -write -limitto "/srv/imposm/config/clip.geojson" -diff -optimize -overwritecache
	docker compose exec imposm imposm import -config "/srv/imposm/config/config.json" -deployproduction
