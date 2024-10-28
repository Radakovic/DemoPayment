user = $(shell id -u)
group = ovo je group id: $(shell getent group)
migration:
	@docker compose exec php bin/console doctrine:migration:migrate -n
	@docker compose exec php bin/console d:s:v
fixtures:
	@docker compose exec php bin/console doctrine:fixtures:load -n
test:
	@docker compose exec php vendor/bin/phpunit
all:
	@echo $(group)
