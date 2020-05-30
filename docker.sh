#!/usr/bin/env bash
containerName="gamble-app"

hostUserId=$(id -u)
dockerUser=www-data

uid=$(id -u)
if [ $uid -gt 100000 ]; then
	uid=1000
fi

#stop potentionally running app
docker-compose stop

##build and launch containers
docker-compose build
docker-compose up -d

# setup permissions
docker exec $containerName chown -R $dockerUser:$dockerUser /var/www

##log into the container
docker exec -it --user $dockerUser $containerName /bin/sh
docker-compose stop
