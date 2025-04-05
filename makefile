.env:
	bash -c 'echo -e "UID=$$(id -u)\nGUID=$$(id -g)"' > .env
start: .env
	docker compose up -d
stop:
	docker compose stop
shell:
	docker compose exec -itu $$(id -u):$$(id -g) web bash
logs:
	docker compose logs web --tail 10 --follow
ps:
	docker compose ps
reset-docker:
	docker compose down -v --rmi all
