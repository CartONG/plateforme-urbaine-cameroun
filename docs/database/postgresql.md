# Setup a remote PostgreSQL / PostGIS access

To access the database from your computer, there are two steps to follow:
- Generate an ssh key and ask the server administrator to add it to the server
- Open an ssh tunnel and connect through this tunnel from your DBMS

## 1. Generate an ssh key
Open Powershell (on Windows) or your bash terminal (Linux)

``` bash
ssh-keygen -t rsa -b 4096 -C "email@exemple.com"
```
By default the key to be transmitted to the administrator will be in ```C:\Users\UserName\.ssh\id_rsa.pub``` (Windows) or ```/home/username/.ssh/rsa_id.pub``` (Linux)

## 2. Open an ssh tunnel
You then need to open an ssh tunnel to redirect your local port to a server port (in our case the PostgreSQL port).
``` bash
ssh -L {PG_PORT}:localhost:{PG_PORT} {USERNAME}@{SERVER_IP}
```
You should now be able to connect to your database in a DBMS using your credentials, ```localhost``` and the port specified in your tunnel