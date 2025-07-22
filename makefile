create-db:
	@echo "Creating databases..."
	@docker exec -it database psql -U postgres -d postgres -c "DROP DATABASE IF EXISTS eco_garden_co WITH (FORCE);"
	@docker exec -it database psql -U postgres -d postgres -c "CREATE DATABASE eco_garden_co;"
