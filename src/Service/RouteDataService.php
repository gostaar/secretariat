<?php
namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class RouteDataService
{
    private $security;
    private $entityManager;
    private $formFactory;

    public function __construct( 
        Security $security,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
    ){
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    public function getFormData(string $fragment)
    {
        $user = $this->security->getUser();

        $serviceNames = ['Administratif', 'Commercial', 'Numerique', 'Agenda', 'Telephonique', 'Repertoire'];
        $services = $this->entityManager->getRepository(\App\Entity\Services::class)->findBy(['name' => $serviceNames]);

        $dossiersDocuments = $this->getDossiersDocumentsByService($services, $user);

        $routeConfig = $this->getRouteConfig($dossiersDocuments, $user);
        $config = $routeConfig[$fragment] ?? $this->createLinkConfig([], $dossiersDocuments['Repertoire']);

        $templateMapping = $this->getTemplateMapping();
        $fragmentTemplate = $templateMapping[$fragment] ?? 'userPage/_mainContent.html.twig';

        $config['sections'] = $fragment;
        $config['fragmentTemplate'] = $fragmentTemplate;
        
        // dump($config);
        return $config;
    }

    private function getDossiersDocumentsByService(array $services, $user): array
    {
        $dossiersDocuments = [];
        foreach ($services as $service) {
            $dossiersFiltered = array_filter($user->getDossiers()->toArray(), fn($dossier) => in_array($service->getName(), $dossier->getServices()->toArray(), true));
            $documentsFiltered = array_filter($user->getDocuments()->toArray(), fn($document) => in_array($document->getDossier(), $dossiersFiltered, true) && $document->getUser() === $user);

            $dossiersDocuments[$service->getName()] = array_merge([
                'dossiers' => $dossiersFiltered,
                'documents' => $documentsFiltered,
            ]);
        }
        return $dossiersDocuments;
    }
    private function getRouteConfig(array $dossiersDocuments, $user): array
    {
        return [
            'link-Acceuil' => $this->createLinkConfig([], $dossiersDocuments['Repertoire']),
            
            'link-Administratif' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], $dossiersDocuments['Administratif']),

            'link-newDocument' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], [
                'dossiers' => $user->getDossiers(),
                'documents' => $user->getDocuments()
            ]),

            'link-Agenda' => $this->createLinkConfig([
                'addEvents' => \App\Form\EventsType::class,
            ], ['events' => $user->getEvents()]),

            'link-MainAgenda' => $this->createLinkConfig([
                'addEvents' => \App\Form\EventsType::class,
            ], ['events' => $user->getEvents()]),

            'link-PageAgenda' => $this->createLinkConfig([ 
                'addEvents' => \App\Form\EventsType::class,             
            ], ['events' => $user->getEvents()]),

            'link-Commercial' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], $dossiersDocuments['Commercial']),

            'link-Numerique' => $this->createLinkConfig([ 
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], $dossiersDocuments['Numerique']),

            'link-Profile' => $this->createLinkConfig([ 
                'userForm' => \App\Form\UserType::class,
            ]), 

            'link-espacepersonnel' => $this->createLinkConfig([], [
                'factures' => $this->mapEntitiesToArray($user->getFactures()),
                'dossiers' => $user->getDossiers(), 
                $dossiersDocuments['Repertoire']
            ]),

            'link-Repertoire' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], $dossiersDocuments['Repertoire']),

            'link-PageRepertoire' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,   
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], $dossiersDocuments['Repertoire']),

            'link-Factures' => $this->createLinkConfig([ 
                'addFacture' => \App\Form\FactureType::class,
                'addFactureLigne' => \App\Form\FactureLigneType::class,
            ], ['factures' => $this->mapEntitiesToArray($user->getFactures())]),

            'link-PageFacture' => $this->createLinkConfig([ 
                'addFacture' => \App\Form\FactureType::class,
                'addFactureLigne' => \App\Form\FactureLigneType::class,
            ], ['factures' => $this->mapEntitiesToArray($user->getFactures())]),

            'link-PageDevis' => $this->createLinkConfig([ 
                'addDevis' => \App\Form\DevisType::class,
                'addDevisLigne' => \App\Form\DevisLigneType::class,
            ], ['devis' => $this->mapEntitiesToArray($user->getDevis())]),

            'link-parametres' => $this->createLinkConfig([], []),

            'link-Telephone' => $this->createLinkConfig([ 
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], $dossiersDocuments['Telephonique']),

            'link-PageDocument' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], [
                'dossiers' => $user->getDossiers(), 
                'documents' => $user->getDocuments(), 
            ]),

            'link-PageDossier' => $this->createLinkConfig([
                'addDossier' => \App\Form\DossierType::class,
                'addDocument' => \App\Form\DocumentsUtilisateurType::class,
                'addTypeDocument' => \App\Form\TypeDocumentType::class,
            ], [
                'dossiers' => $user->getDossiers(), 
                'documents' => $user->getDocuments(), 
            ]),
        ];
    }
      
    private function createLinkConfig(array $formClasses, array $additionalData = []): array
    {
        $forms = [];
        foreach ($formClasses as $formAlias => $formClass) {
            
            $entity = null;
            
            switch ($formClass) {
                case \App\Form\ContactType::class:
                    $entity = new \App\Entity\Contact();
                    break;
                case \App\Form\DevisType::class:
                    $entity = new \App\Entity\Devis();
                    break;
                case \App\Form\DevisLigneType::class:
                    $entity = new \App\Entity\DevisLigne();
                    break;
                case \App\Form\DevisVersionType::class:
                    $entity = new \App\Entity\DevisVersion();
                    break;
                case \App\Form\DocumentsUtilisateurType::class:
                    $entity = new \App\Entity\DocumentsUtilisateur();
                    break;
                case \App\Form\DossierType::class:
                    $entity = new \App\Entity\Dossier();
                    break;
                case \App\Form\EventsType::class:
                    $entity = new \App\Entity\Events();
                    break;
                case \App\Form\FactureLigneType::class:
                    $entity = new \App\Entity\FactureLigne();
                    break;
                case \App\Form\FactureType::class:
                    $entity = new \App\Entity\Facture();
                break;
                case \App\Form\RepertoireType::class:
                    $entity = new \App\Entity\Repertoire();
                    break;
                case \App\Form\ServicesType::class:
                    $entity = new \App\Entity\Services();
                    break;
                case \App\Form\TypeDocumentType::class:
                    $entity = new \App\Entity\TypeDocument();
                    break;
                case \App\Form\UserType::class:
                    $entity = new \App\Entity\User();
                    break;
                default:
                throw new \InvalidArgumentException("Impossible de trouver l'entité associée à {$formClass}.");
            }
                        
            $form = $this->formFactory->create($formClass, $entity);
            $forms[$formAlias] = $form->createView();
        }
        
        return [
            'user' => $this->getUser(),
            'services' => $this->getUser()->getServices(),
            ...$forms, 
            ...$additionalData,
        ];
    }
    
    private function mapEntitiesToArray($entities): array
    {
        return array_map(fn($entity) => $entity->toArray(), $entities->toArray());
    }
    
    public function getUser()
    {
        return $this->security->getUser(); 
    }

    public function getTemplateMapping(): array
    {
        return [
            'link-Acceuil' => 'userPage/_mainContent.html.twig',
            'link-Administratif' => 'partials/user/Administratif/_documents.html.twig',
            'link-newDocument' => 'partials/user/Administratif/_newDocuments.html.twig',
            'link-Agenda' => 'partials/user/Agenda/_agenda.html.twig',
            'link-MainAgenda' => 'partials/user/Agenda/_agendaMain.html.twig',
            'link-PageAgenda' => 'partials/user/Agenda/agenda.html.twig',
            'link-Commercial' => 'partials/user/Commercial/_commercial.html.twig',
            'link-Numerique' => 'partials/user/Numerique/_numerique.html.twig',
            'link-Profile' => 'partials/user/Profile/_profile.html.twig',
            'link-espacepersonnel' => 'partials/user/Profile/_espacepersonnel.html.twig',
            'link-Repertoire' => 'partials/user/Profile/_repertoire.html.twig',
            'link-PageRepertoire' => 'partials/user/Profile/repertoire.html.twig',
            'link-Factures' => 'partials/user/Profile/_factures.html.twig',
            'link-PageFacture' => 'partials/user/Profile/facture.html.twig',
            'link-PageDevis' => 'partials/user/Profile/devis.html.twig',
            'link-parametres' => 'partials/user/Profile/_parametres.html.twig',
            'link-Telephone' => 'partials/user/Telephone/_telephone.html.twig',
            'link-PageDocument' => 'partials/user/document.html.twig',
            'link-PageDossier' => 'partials/user/dossier.html.twig',
        ];
    }

    // public function getServiceMapping(): array
    // {
    //     return [
    //         'link-Administratif' => ['Administratif'],
    //         'link-newDocument' => ['Administratif'],
    //         'link-Repertoire' => ['Repertoire'],
    //         'link-PageRepertoire' => ['Repertoire'],
    //         'link-Agenda' => ['Agenda'],
    //         'link-MainAgenda' => ['Agenda'],
    //         'link-PageAgenda' => ['Agenda'],
    //         'link-Commercial' => ['Commercial'],
    //         'link-Numerique' => ['Numerique'],
    //         'link-Telephone' => ['Telephonique'],
    //     ];
    // }
}
