Sur l'ordinateur doit être installé node et composer 

####### DOCKER ########
TOUT SUPPRIMER: 
docker system prune -a --volumes
#######################

####### SQL ######
composer install
npm install
docker-compose up -d
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
npm run dev
docker-compose exec mysql mysql -u root -p
USE symfony;
INSERT INTO user (email, password, roles, nom, adresse, code_postal, ville, pays, telephone, mobile, siret, nom_entreprise) VALUES ('user@example.com', '$2y$13$/07W5XdhdJOm2LZypCHvPe76Ly2W/E.DfsQtA0Q0rhbVTwz4stkyC', '["ROLE_USER"]', '', '', '', '', '', '', '', '', '');
INSERT INTO user (email, password, roles, nom, adresse, code_postal, ville, pays, telephone, mobile, siret, nom_entreprise) VALUES ('admin@example.com', '$2y$13$YslyT/k0PEob7Us7Kauc.u2cKlK8LANqcGpnAdDdrItHt6TdsHv.a', '["ROLE_ADMIN"]', '', '', '', '', '', '', '', '', '');
###################

####### INFOS #########
email: user@example.com 
password: user

email: admin@example.com
password: admin
#######################

####### HASH ##########
php bin/console security:hash-password
#######################