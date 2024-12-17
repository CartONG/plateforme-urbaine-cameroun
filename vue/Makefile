YARN = yarn

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
yarn: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(VUE) $(YARN) $(c)

vue-type-check: ## Run the TypeScript type checker
	@$(VUE) $(YARN) type-check
