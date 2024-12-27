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

# **Procédure d'ajout de fragment**

## **1. Dans le template**
### **a) Définir les besoins**
- Déterminez si un **fragment supplémentaire** est nécessaire au niveau de l'URL.

### **b) Création d'un bouton pour le nouveau fragment**
- Créez un bouton avec les attributs `data-fragment` appropriés pour accéder au nouveau template.

**Exemple :**
```html
<div class="d-flex flex-column align-items-center p-1" style="position: relative; width: 130px;">
    <button 
        type="button" 
        data-fragment="link-PageRepertoire"
        data-dossier="{{ dossier.id }}"  <!-- Nouveau fragment dans l'URL -->
        class="change-fragment btn d-flex flex-column flex-start align-items-center text-decoration-none"
        style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: transparent; border: none; z-index: 1; padding: 0;">
    </button>
    <i class="fas fa-folder fa-3x text-center text-warning"></i>
    <p class='text-black'>{{ dossier.name }}</p>
</div>

# Procédure d'ajout de fragment

## 2. Dans le contrôleur
### a) Vérifiez la présence du nouveau fragment
Utilisez `Request` pour récupérer le fragment depuis l'URL :
```php
$dossierId = $request->query->get('dossier');
```

### b) Gérez la condition d'existence
Si le fragment est requis, passez sa valeur (ou `null`) au service :
```php
$formData = $this->routeDataService->getFormData($fragment, $dossierId ? (int) $dossierId : null);
```

### c) Mettez à jour la route AJAX
Vérifiez que la route `/changefragment` gère aussi le nouveau fragment. Cela permettra de mettre à jour les données via les requêtes AJAX.

## 3. Dans le service
### a) Préparez la route
Ajoutez le support du nouveau fragment dans `RouteDataService.php`.

### b) Mettez à jour `getFormData()`
Ajoutez un nouvel argument optionnel pour prendre en charge le fragment :
```php
public function getFormData(string $fragment, ?int $dossierId = null): array
```

### c) Mettez à jour `getTemplateMapping()`
Associez le fragment à son template correspondant :
```php
'link-Acceuil' => 'userPage/_mainContent.html.twig',
```

### d) Ajoutez le fragment dans `getRouteConfig()`
Vérifiez si le fragment est défini avant de l'envoyer à la fonction :
```php
$dossierId 
    ? $routeConfig = $this->getRouteConfig($dossiersDocuments, $user, $dossierId) 
    : $routeConfig = $this->getRouteConfig($dossiersDocuments, $user);
```

Adaptez la signature de la fonction pour inclure le fragment :
```php
private function getRouteConfig(array $dossiersDocuments, $user, ?int $dossierId = null): array
```

### e) Construisez les données nécessaires
Ajoutez les entités et formulaires requis dans la configuration des routes.

**Exemple :**
```php
'link-PageRepertoire' => $this->createLinkConfig([
    'addContact' => \App\Form\ContactType::class,   
    'addRepertoire' => \App\Form\RepertoireType::class,
], [
    'dossier' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Dossier::class)->find($dossierId) : null,
    'repertoires' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Repertoire::class)->findBy(['dossier' => $dossierId]) : [],
    'contacts' => $this->entityManager->getRepository(\App\Entity\Contact::class)->findAll(),
]),
```

### f) Ajoutez les recherches d'entités
Vérifiez la présence du fragment et appliquez les fonctions de recherche nécessaires :
```php
[
    'dossier' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Dossier::class)->find($dossierId) : null,
    'repertoires' => $dossierId ? $this->entityManager->getRepository(\App\Entity\Repertoire::class)->findBy(['dossier' => $dossierId]) : [],
    'contacts' => $this->entityManager->getRepository(\App\Entity\Contact::class)->findAll(),
],
```

### g) Référencement dans les switches
Si une nouvelle entité est introduite, ajoutez-la dans le `switch` pour gérer les vues :
```php
...\App\Entity\Contact)->createView();
```

## IMPORTANT
Les données supplémentaires (au-delà de `services` et `user`) doivent être vérifiées et ajoutées dans le deuxième tableau lors de l'appel de `createLinkConfig()`.
