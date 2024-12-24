## Sur l'ordinateur doit être installé node et composer 

# ####### A FAIRE ######
docker-compose up -d
docker-compose exec apache php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec apache php /var/www/html/bin/console doctrine:fixtures:load --no-interaction

### Docker
TOUT SUPPRIMER: 
docker system prune -a --volumes

### Commandes utiles
composer install
npm install
npm run dev
php bin/console make:migration
docker-compose exec apache php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction

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