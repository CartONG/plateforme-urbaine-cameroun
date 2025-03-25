YARN = yarn

##
## --------------------------------------------------------------------------------
##  🧩 Yarn
## --------------------------------------------------------------------------------

yarn: ## Yarn commands or pass the parameter "c=" to run a given command, example: make yarn c="add vue"
	@$(eval c ?=)
	@$(VUE) $(YARN) $(c)

yarn-install: ## Install all dependencies
	@$(VUE) $(YARN) install

##
## --------------------------------------------------------------------------------
##  🧹 Eslint
## --------------------------------------------------------------------------------

vue-type-check:	## Run the TypeScript type checker
	@$(VUE) $(YARN) type-check
