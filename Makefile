include vue/Makefile
include symfony/Makefile

-include local.mk
-include .env
-include .env.local

ifdef ENV
env := $(ENV)
else
env := prod
endif

DOCKER_COMP = docker compose --env-file .env --env-file .env.${ENV} --env-file .env.local

DOCKER_EXEC_VUE = $(DOCKER_COMP) exec vue
DOCKER_EXEC_PHP = $(DOCKER_COMP) exec frankenphp

VUE = $(DOCKER_EXEC_VUE)
SYMFONY = $(DOCKER_EXEC_PHP) php bin/console
COMPOSER = $(DOCKER_EXEC_PHP) composer

dev: up show-urls
build-dev: build-and-up show-urls

init: build init-jwt-keypair init-hosts
build-and-up: build up

up:
	$(DOCKER_COMP) up -d --remove-orphans

down:
	$(DOCKER_COMP) down

ifeq (restart, $(firstword $(MAKECMDGOALS)))
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(RUN_ARGS):;@:)
endif

restart:
	$(DOCKER_COMP) restart $(RUN_ARGS)

restart-db-container:
	make restart postgres

rm-vue-volume:
	$(DOCKER_COMP) down vue -v

reload-caddy:
	$(DOCKER_COMP) down frankenphp
	$(DOCKER_COMP) build frankenphp
	$(DOCKER_COMP) up -d frankenphp

down-remove-all:
	$(DOCKER_COMP) down --remove-orphans --rmi all -v

build:
	$(DOCKER_COMP) build

build-no-cache:
	$(DOCKER_COMP) build --no-cache

deploy: build-and-up init-jwt-keypair cc

docker-config:
	$(DOCKER_COMP) config

YELLOW=\033[1;33m
GREEN=\033[1;32m
BLUE=\033[0;34m
LIGHT_BLUE=\033[1;36m
NC=\033[0m # No Color

show-urls:
	@echo ""
	@printf "${BLUE}+-------------------------------------------------+\n"
	@printf "${BLUE}| Cameroon Urban Platform                         |\n"
	@printf "${BLUE}+-------------------------------------------------+\n"
	@printf "${BLUE}| ${BLUE}%-19s ${BLUE}| ${LIGHT_BLUE}%-27s${BLUE} |\n" "🚀  Main website" 	"https://puc.local"
	@printf "${BLUE}| ${BLUE}%-19s ${BLUE}| ${LIGHT_BLUE}%-27s${BLUE} |\n" "🔒  REST API Doc" 			"https://puc.local/api/docs"
	@printf "${BLUE}| ${BLUE}%-19s ${BLUE}| ${LIGHT_BLUE}%-27s${BLUE} |\n" "🔭  Nominatim API" 			"https://puc.local/nominatim"
	@printf "${BLUE}| ${BLUE}%-19s ${BLUE}| ${LIGHT_BLUE}%-27s${BLUE} |\n" "🌍  QGIS server" 	 	"https://qgis.puc.local"
	@printf "${BLUE}| ${BLUE}%-19s ${BLUE}| ${LIGHT_BLUE}%-27s${BLUE} |\n" "📨  SMTP server" 		"https://mail.puc.local"
	@printf "${BLUE}| ${BLUE}%-19s ${BLUE}| ${LIGHT_BLUE}%-27s${BLUE} |\n" "💡  Documentation" 	"https://docs.puc.local"
	@printf "${BLUE}+-------------------------------------------------+${NC}\n"
	@echo ""

HOST_ENTRIES = \
  "127.0.0.1     puc.local"\
  "127.0.0.1     qgis.puc.local"\
  "127.0.0.1     docs.puc.local"\
  "127.0.0.1     mail.puc.local"

init-hosts:
	@printf "${YELLOW}----------------------------------------------------${NC}\n"
	@printf "${YELLOW}  We will just add entries to your hosts file       ${NC}\n"
	@printf "${YELLOW}  Note : You might be asked to enter your password  ${NC}\n"
	@printf "${YELLOW}----------------------------------------------------${NC}\n"
	@echo "Detecting OS..."
	@OS=`uname | cut -d'_' -f1` ; \
	if [ "$$OS" = "Linux" ] || [ "$$OS" = "Darwin" ]; then \
		HOSTS_FILE="/etc/hosts"; \
		for ENTRY in $(HOST_ENTRIES); do \
			if ! grep -qF "$$ENTRY" "$$HOSTS_FILE"; then \
				echo "Adding entry '$$ENTRY'"; \
				echo "$$ENTRY" | sudo tee -a "$$HOSTS_FILE"; \
			else \
				echo "Entry '$$ENTRY' already exists"; \
			fi; \
		done; \
	elif [ "$$OS" = "MINGW32" -o "$$OS" = "MINGW64" -o "$$OS" = "MSYS" ]; then \
		HOSTS_FILE="C:\\Windows\\System32\\drivers\\etc\\hosts"; \
		for ENTRY in $(HOST_ENTRIES); do \
			powershell -Command "if (!(Select-String -Path '$$HOSTS_FILE' -Pattern '$$ENTRY' -Quiet)) { Start-Process cmd -Verb runAs -ArgumentList '/c echo $$ENTRY >> $$HOSTS_FILE' } else { Write-Output 'Entry already exists for Windows : $$ENTRY' }"; \
		done; \
	else \
		echo "Unsupported OS"; \
	fi
