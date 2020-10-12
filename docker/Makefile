composer-install:
	docker run --rm --interactive --tty --volume $PWD:/app composer install

up:
	docker-compose up -d

generate:
	docker-compose run --rm php php yii queue/generate

generate-alternative:
	docker-compose run --rm php php yii queue/generate-consec

process:
	docker-compose run --rm php php yii queue/process

log:
	docker-compose run --rm php php yii queue/log $(account_id)