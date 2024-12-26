<?php

namespace App\Controller\User;

use App\Entity\Devis;
use App\Entity\DevisLigne;
use App\Entity\DocumentsUtilisateur;
use App\Entity\Dossier;
use App\Entity\Events;
use App\Entity\Facture;
use App\Entity\FactureLigne;
use App\Entity\Repertoire;
use App\Entity\Services;
use App\Entity\TypeDocument;
use App\Entity\User;
use App\Enum\DevisStatus;
use App\Enum\FactureStatus;
use App\Form\DevisType;
use App\Form\DevisLigneType;
use App\Form\DocumentsUtilisateurType;
use App\Form\DossierType;
use App\Form\FactureType;
use App\Form\FactureLigneType;
use App\Form\RepertoireType;
use App\Form\ServicesType;
use App\Form\TypeDocumentType;
use App\Repository\EventsRepository;
use App\Service\DossierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Cache\CacheItemPoolInterface;

class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private EntityManagerInterface $em;
    private DossierService $dossierService;
    private CacheItemPoolInterface $cache;
    
    public function __construct(
        EventsRepository $eventsRepository, 
        EntityManagerInterface $em,
        DossierService $dossierService,
        CacheItemPoolInterface $cache
    ){
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
        $this->dossierService = $dossierService;
        $this->cache = $cache;
    }

    /**
     * Get the user data (factures, devis, etc.)
     */
    private function getUserData(): array
    {
        $user = $this->getUser();
        $cacheKey = 'user_data_' . $user->getId();

        $cachedData = $this->cache->getItem($cacheKey);
        
        $userData = [
            'user' => $user,
            'factures' => $this->mapEntitiesToArray($user->getFactures()),
            'devis' => $this->mapEntitiesToArray($user->getDevis()),
            'services' => $user->getServices(),
            'repertoires' => $user->getRepertoires(),
            'documents' => $user->getDocuments(),
            'dossiers' => $user->getDossiers(),
            'events' => $user->getEvents(),
        ];

        if ($cachedData->isHit()) {
            // return $cachedData->get();
            $this->cache->delete($cacheKey);
        }

        $cachedData->set($userData);
        $cachedData->expiresAfter(3600);
        $this->cache->save($cachedData);

        return $userData;
    }

    /**
     * Helper function to map entities to arrays
     */
    private function mapEntitiesToArray($entities): array
    {
        return array_map(fn($entity) => $entity->toArray(), $entities->toArray());
    }
    
    /**
     * Index route for the user page
     */
    #[Route('/user', name: 'user')]
    public function index(EventsRepository $eventsRepository, Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        $user = $this->getUser();
        $userData = $this->getUserData();
        $forms = $this->getForms();
        $sections = $this->getFragmentSections();
        
        $dossierId = $request->query->get('dossier');
        $dossier = null;
        if ($dossierId) {$dossier = $this->dossierService->getDossier($dossierId);}

        $fragment = $request->query->get('fragment', 'link-Acceuil');
        $filteredDossiers = $this->filterDossiersByService($fragment, $user->getDossiers()->toArray());

        $viewData = array_merge($userData, [
            'currentFragment' => $fragment,
            'filteredDossiers' => $filteredDossiers,
            'dossier' => $dossier,
            'events' => $events,
            'forms' => $forms,
            'sections' => $sections,
        ]);

        
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'fragmentContent' => $this->renderView('userPage/_fragmentContent.html.twig', $viewData)
            ]);
        }
        
        return $this->render('userPage/user.html.twig', $viewData);
    }
    
    /**
     * Retrieve and return forms
     */
    private function getForms()
    {   
        new FactureLigne();
        return [
            'addDossier' => $this->createForm(DossierType::class, new Dossier())->createView(),
            'addFacture' => $this->createForm(FactureType::class, new Facture())->createView(),
            'addDevis' => $this->createForm(DevisType::class, new Devis())->createView(),
            'addDevisLigne' => $this->createForm(DevisLigneType::class, new DevisLigne())->createView(),
            'addService' => $this->createForm(ServicesType::class, new Services())->createView(),
            'addDocument' => $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur())->createView(),
            'addRepertoire' => $this->createForm(RepertoireType::class, new Repertoire())->createView(),
            'addTypeDocument' => $this->createForm(TypeDocumentType::class, new TypeDocument())->createView(),
        ];
    }
    
    /**
     * Get the sections for the fragment
     */
    private function getFragmentSections(): array
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
            'link-PageDossier' => 'partials/user/dossier.html.twig'
        ];
    }

    /**
     * Get a mapping of services
     */
    private function getServiceMapping(): array
    {
        return [
            'link-Administratif' => ['Administratif'],
            'link-newDocument' => ['Administratif'],
            'link-Repertoire' => ['Repertoire'],
            'link-PageRepertoire' => ['Repertoire'],
            // 'link-espacepersonnel' => ['Repertoire'],
            // 'link-Profile' => ['Espace Personnel'],
            // 'link-Factures' => ['Espace Personnel'],
            // 'link-PageFacture' => ['Espace Personnel'],
            // 'link-PageDevis' => ['Espace Personnel'],
            // 'link-parametres' => ['Espace Personnel'],
            'link-Agenda' => ['Agenda'],
            'link-MainAgenda' => ['Agenda'],
            'link-PageAgenda' => ['Agenda'],
            'link-Commercial' => ['Commercial'],
            'link-Numerique' => ['Numérique'],
            'link-Telephone' => ['Téléphonique'],
        ];
    }

    /**
     * Filter dossiers by service based on fragment
     */
    private function filterDossiersByService(?string $fragment, array $dossiers): array
    { 
        $serviceMapping = $this->getServiceMapping();

        if ($fragment && isset($serviceMapping[$fragment])) {
            $servicesToFilter = $serviceMapping[$fragment];
            $filteredDossiers = array_filter($dossiers, function($dossier) use ($servicesToFilter) {
                $dossierService = $dossier->getServices(); 
                if ($dossierService instanceof Services) {
                    $dossierServiceName = $dossierService->getName();
                    return !empty(array_intersect([$dossierServiceName], $servicesToFilter));
                }
                return false;
            });
        } else {
            $filteredDossiers = $dossiers;
        }
        return $filteredDossiers;
    }
    
    #[Route('/user_agenda', name: 'user_agenda', methods: ['GET'])]
    public function agenda(EventsRepository $eventsRepository, Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        $userData = $this->getUserData();
        
        return $this->render('user/agenda.html.twig', array_merge($userData, [
            'currentRoute' => 'user_agenda',
            'events' => $events,
        ]));
    }
}