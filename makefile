refresh:
	@echo "Refreshing the database..."
	@make create-db
	@make schema-update
	@make fixtures-load

create-db:
	@echo "Creating databases..."
	@docker exec -it database psql -U postgres -d postgres -c "DROP DATABASE IF EXISTS eco_garden_co WITH (FORCE);"
	@docker exec -it database psql -U postgres -d postgres -c "CREATE DATABASE eco_garden_co;"

schema-update:
	@docker exec -it eco_garden_co php bin/console doctrine:schema:update --force

make-migration:
	@docker exec -it eco_garden_co php bin/console make:migration

fixtures-load:
	@docker exec -it eco_garden_co php bin/console doctrine:fixtures:load
