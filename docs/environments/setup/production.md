# 🚀 Setup - Production environment

## Requirements

### Minimal server requirements
-  Ubuntu LTS (24.04)
-  16GB RAM
-  256GB SSD (Make sure to have scalabale storage)
-  8 cores

### Tools requirements
- [**Docker**](https://docs.docker.com/desktop/) with compose plugin

##
### 2 Setup options

There is **two setup options**, choose the one that fits your need.
- [**Option A**](#🚀-option-a-single-application) : **Single application**
- [**Option B**](#🎛️-option-b-mutliple-applications-behind-a-reverse-proxy): **Mutliple applications behind a Reverse Proxy** (if you want to host multiple applications on this server)

## 🚀 Option A : Single application

### *1 : Setup project*

``` bash
# Clone your project
git clone git@github.com:CartONG/plateforme-urbaine-cameroun.git puc_prod && cd puc_prod

# Create an .env.local
nano .env.local
```

Add this content to the `.env.local` :

``` bash
# Your .env.local
ENV=prod  # this one will get the appropriate docker compose files for the setup 
DOMAIN=<your-domain.org>

POSTGRES_PASSWORD=
NOMINATIM_PASSWORD=
APP_SECRET=
MAILER_DSN=
```


### *3 : Setup DNS*

Then set up your DNS records :
Make sure to add these two `A` entries :
- `your-domain.org`
- `*.your-domain.org`

### *4 : Up*
Run the following command 

``` bash
make deploy
```


### *5 : Init the database*
Run the following commands

``` bash
make run-migration
make run-fixtures # if you want to load fake data
```

### *6 : Test*

And check everything is accessible when accessing `https://your-domain.org`


## 🎛️ **Option B** : Mutliple applications behind a Reverse Proxy

### *1 : Setup project*

Replicate [**Option A**](#option-a-single-server) operations.

But add as well the following lines to it.

``` bash
# append these lines to the end of the file
COMPOSE_FILE=compose.yaml:compose.uat.yaml:compose.prod.yaml
# set as 'http' because app under a caddy reverse proxy which handles https
CADDY_PROTOCOL=http

# Change app frankenphp caddy exposed ports to let 80 and 443 open for the root reverse proxy
HTTPS_PORT=444
HTTP_PORT=81
```

Restart your containers.

### *2 : Create Reverse proxy directory structure and files*

Then create the directory `puc_reverse_proxy` that will contain the caddy docker service that will proxy the requests. Your directory structure should look like this :

- `~`
  - `puc_prod`
  - `puc_reverse_proxy`
    - `compose.yaml`
    - `Caddyfile`

Create these two files :

``` yaml
# ./puc_reverse_proxy/compose.yaml

services:
  caddy:
    image: caddy:2.8.4-alpine
    restart: unless-stopped
    cap_add:
      - NET_ADMIN
    ports:
      - "80 :80"
      - "443 :443"
      - "443 :443/udp"
    volumes:
      - $PWD/Caddyfile:/etc/caddy/Caddyfile
      - $PWD/site:/srv
      - caddy_data:/data
      - caddy_config:/config
    networks:
      - puc-network

volumes:
  caddy_data:
    external: true
  caddy_config:

networks:
  puc-network:
    name: puc_network
    external: true
```

Add the following `Caddyfile` file, that will redirect requests to the appropriate docker service, and will handle certificates :

::: info
In the following file sample , make sure to replace `<your-domain.org>` with your actual domain, example `puc.org`
:::

``` bash
# ./puc_reverse_proxy/Caddyfile

{
  on_demand_tls {
    ask http://localhost:9999
  }
}

# Update <your-domain.org> one with your domain
<your-domain.org> {
  reverse_proxy frankenphp:81
}

# Update <your-domain.org> one with your domain
*.<your-domain.org> {
  tls {
    on_demand
  }
  reverse_proxy frankenphp:81
}

http://localhost:9999 {
  respond 200
}
```

### *3 : Pull and up your Caddy service*

Once created, simply run :

``` bash
docker compose up -d
```
