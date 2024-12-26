## Sur l'ordinateur doit être installé node et composer 

# ####### A FAIRE ######
docker-compose up -d
docker exec secretariat-php-1 php /code/bin/console doctrine:migrations:migrate --no-interaction
docker exec secretariat-php-1 php /code/bin/console doctrine:fixtures:load --no-interaction

### Docker
docker ps
docker exec -it secretariat-php-1 sh
TOUT SUPPRIMER: 
docker system prune -a --volumes

### Commandes utiles
composer install
npm install
npm run dev
php bin/console make:migration
docker-compose exec secretariat-php-1 php /code/bin/console doctrine:migrations:migrate --no-interaction

### INFOS
docker-compose exec mysql mysql -u root -p
password: secret

email: user@example.com 
password: user

email: admin@example.com
password: admin

### HASH
php bin/console security:hash-password

### APACHE
docker-compose exec apache service apache2 restart