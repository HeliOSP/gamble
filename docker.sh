#!/usr/bin/env bash
containerName="gamble-app"

uid=$(id -u)
if [ $uid -gt 100000 ]; then
	uid=1000
fi

#stop potentionally running app
docker-compose stop

##build and launch containers
docker-compose build
docker-compose up -d

docker exec -it $containerName /bin/sh
docker-compose stop
