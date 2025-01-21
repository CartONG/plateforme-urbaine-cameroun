YARN = yarn

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
yarn: ## Run yarn or pass the parameter "c=" to run a given command, example: make yarn c="add @vee-validate/i18n"
	@$(eval c ?=)
	@$(VUE) $(YARN) $(c)

vue-type-check: ## Run the TypeScript type checker
	@$(VUE) $(YARN) type-check
