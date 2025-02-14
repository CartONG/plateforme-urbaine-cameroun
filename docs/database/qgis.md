# Setup a PostGIS connexion in QGIS
To access the database from QGIS Desktop, there are two steps to follow:
- Create or modify a ```pg_service.conf``` file and define a service inside
- Set a PostGIS connexion based on the defined service

## 1. Setup PGService file
The first step is to check if a PGService file is configured in QGIS. Click on Preferences > Options > System and go down to the Common Environment Variables panel and check if you have an entry associated with the PGSERVICEFILE variable.

If the file exists, you will only have to edit it. If not, create an empty pg_service.conf file and specify the path to this file in QGIS.

## 2. Configure PGService
You can now add a new service in the pg_service.conf file.
Open it in a text editor and configure it like this:

``` ini
[puc]
host=localhost
port={your tunnel port}
dbname={db name}
user={username}
password={password}
```

**Do not change the service name (puc), this name will be saved in the QGIS projects that you load into the application to allow it to read the data.**

You can now create a connection to postgresql in QGIS, you will only need to fill in the service name (puc) in the connection information!