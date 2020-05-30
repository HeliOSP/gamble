# gamble
run `sh docker.sh`
you will get to terminal in docker

run `php composer.phar install` to get all dependencies

run `bin/console doctrine:database:create`
run `bin/console doctrine:schema:create`
run `bin/console foctrine:fixtures:load`

go to http://localhost:8080/index.php/

to stop docker run in docker terminal `exit`