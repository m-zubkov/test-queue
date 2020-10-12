composer-install:
	docker run --rm --interactive --tty --volume ${PWD}/../:/app composer install

migrate:
	docker-compose run --rm php php yii migrate

up:
	docker-compose up -d

down:
	docker-compose down

generate:
	docker-compose run --rm php php yii queue/generate

generate-alternative:
	docker-compose run --rm php php yii queue/generate-consec

process:
	docker-compose run --rm php php yii queue/process

log:
	docker-compose run --rm php php yii queue/log $(account_id)
