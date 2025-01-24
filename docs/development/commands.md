# Commands

While developping on the project there is different type of commands that might be useful :

## Makefile commands

Makefile files have been created to **fasten development** there is 3 main ones :
- `./Makefile` : Contains all Docker actions
- `./symfony/Makefile` : Contains all commands related to Symfony (Symfony, Composer, Doctrine, ...)
- `./vue/Makefile` : Contains all commands related to the frontend (yarn, eslint)

All commands are accessible using `make` command from the root directory of the project as sub-Makefiles are included in the root one. 

::: info
If you work on the project it is **highly recommended that you check the available commands inside those files** to fasten your development.
:::


### `make help` command

``` bash
# List all available Makefile commands using the help command
make help
```

**Outputs :**
```
--------------------------------------------------------------------------------
 🚀 Main commands
--------------------------------------------------------------------------------
init:           Init the project
dev:            Up and show urls
build-dev:      Build, up and show urls
deploy:         Deploys the project
show-urls:      Show project services urls
init-hosts:     Adds the puc.local and local subdomain entries to the hosts file

--------------------------------------------------------------------------------
 🐋  Docker commands
--------------------------------------------------------------------------------
build-and-up:   Builds and up all containers using current env files
build:          Builds all containers using current env files
...
```