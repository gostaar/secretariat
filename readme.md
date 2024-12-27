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
docker exec -it secretariat-php-1 php /code/bin/console doctrine:migrations:migrate --no-interaction

### INFOS
docker exec secretariat-mysql-1 mysql -u root -p
use symfony;

password: secret

email: user@example.com 
password: user

email: admin@example.com
password: admin

### HASH
php bin/console security:hash-password

### APACHE
docker-compose exec apache service apache2 restart

## Procédure d'ajout de fragment
1) dans le template:
    a) on détermine ce qu'on a besoin au niveau de l'url => faut-il un fragment supplémentaire?
    b) on créer un button avec les bons data-fragments pour accéder à notre nouveau template
    (ex: 
        <div class="d-flex flex-column align-items-center p-1" style="position: relative; width: 130px;">
            <button 
                type="button" 
                data-fragment="link-PageRepertoire"
                data-dossier="{{ dossier.id }}"  <!-- => ici ce serait pour un nouveau fragment dans l'url -->
                class="change-fragment btn d-flex flex-column flex-start align-items-center text-decoration-none"
                style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: transparent; border: none; z-index: 1; padding: 0;">
            </button>

            <i class="fas fa-folder fa-3x text-center text-warning"></i>
            <p class='text-black'>{{ dossier.name }}</p>
        </div>
    )
2) dans le controller: 
    a) on vérifie si le nouveau fragment se trouve dans l'url: $dossierId = $request->query->get('dossier');
    b) on détermine si le nouveau fragment est null ou non (les pages ou on a pas besoin du nouveau fragment) (ex: $formData = $this->routeDataService->getFormData($fragment, $dossierId ? (int) $dossierId : null);)

    x) Ne pas oublier de faire la même pour la route /changefragment pour que les data se mettent à jour depuis ajax

3) dans le service: 
    a) on prépare notre route: dans RouteDataService.php
    b) on ajoute le nouveau fragment à la fonction principale getFormData() (ex: getFormData(?int $dossierId = null))
    c) getTemplateMapping() -> on fait correspondre notre template avec un nom de fragment (ex: 'link-Acceuil' => 'userPage/_mainContent.html.twig')

    IMPORTANT
    d) lors de l'appel à getRouteConfig(), on vérifie si le nouveau fragment existe et on l'envoie comme argument à la fonction
    (ex: $dossierId ? $routeConfig = $this->getRouteConfig($dossiersDocuments, $user, $dossierId) : $routeConfig = $this->getRouteConfig($dossiersDocuments, $user);)
    e) dans la fonction getRouteConfig, on ajoute le nouveau fragment: getRouteConfig(array $dossiersDocuments, $user, ?int $dossierId = null): array
    f) et on construit les éléments nécessaires, à savoir, user et services sont automatiquement définis:
    (ex:
        'link-PageRepertoire' => $this->createLinkConfig([
            'addContact' => \App\Form\ContactType::class,   
            'addRepertoire' => \App\Form\RepertoireType::class,
        ], [
            'dossier' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Dossier::class)->find($dossierId) : null,
            'repertoires' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Repertoire::class)->findBy(['dossier' => $dossierId]) : [],
            'contacts' =>$this->entityManager->getRepository(\App\Entity\Contact::class)->findAll(),
        ]),
    )
    explications: 'addContact' => \App\Form\ContactType::class,  correspond à 'addContact' => $this->formFactory->create(\App\Form\ContactType::class, ...
    une recherche d'entité se fait via un switch, donc s'il y a une nouvelle entité, il faut la référencer dans le switch!!
    ...\App\Entity\Contact)->>createView()

    g) les datas supplémentaires à services et user sont ajouté dans le deuxième tableau: doit vérifier si le nouveau fragment existe, puis on applique les fonctions communes de recherche ...
    ex:  [
            'dossier' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Dossier::class)->find($dossierId) : null,
            'repertoires' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Repertoire::class)->findBy(['dossier' => $dossierId]) : [],
            'contacts' =>$this->entityManager->getRepository(\App\Entity\Contact::class)->findAll(),
        ],