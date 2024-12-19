<?php

namespace App\Controller\User;

use App\Entity\Events;
use App\Entity\User;
use App\Repository\EventsRepository;
use App\Enum\DevisStatus;
use App\Enum\FactureStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Entity\DocumentsUtilisateur;
use App\Form\DocumentsUtilisateurType;
use App\Entity\Dossier;
use App\Form\DossierType;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Entity\Repertoire;
use App\Form\RepertoireType;
use App\Entity\Services;
use App\Form\ServicesType;

use App\Service\DossierService;
use App\Entity\TypeDocument;
use App\Form\TypeDocumentType;

class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private EntityManagerInterface $em;
    private DossierService $dossierService;
    
    public function __construct(
        EventsRepository $eventsRepository, 
        EntityManagerInterface $em,
        DossierService $dossierService
    ){
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
        $this->dossierService = $dossierService;
    }

    private function getUserData(): array
    {
        $user = $this->getUser();

        $factures = $user->getFactures()->toArray();
        $facturesArray = array_map(fn($facture) => $facture->toArray(), $factures);

        $devis = $user->getDevis()->toArray();
        $devisArray = array_map(fn($devi) => $devi->toArray(), $devis);

        return [
            'user' => $user,
            'factures' => $facturesArray,
            'devis' => $devisArray,
            'services' => $user->getServices(),
            'repertoires' => $user->getRepertoires(),
            'documents' => $user->getDocuments(),
            'dossiers' => $user->getDossiers(),
        ];
    }
    
    #[Route('/user', name: 'user')]
    public function index(EventsRepository $eventsRepository, Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        $user = $this->getUser();
        $userData = $this->getUserData();

        $dossierForm = $this->createForm(DossierType::class, new Dossier());
        $factureForm = $this->createForm(FactureType::class, new Facture());
        $devisForm = $this->createForm(DevisType::class, new Devis());
        $serviceForm = $this->createForm(ServicesType::class, new Services());
        $documentForm = $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur());
        $repertoireForm = $this->createForm(RepertoireType::class,  new Repertoire());
        $typeDocumentForm = $this->createForm(TypeDocumentType::class, new TypeDocument());

        $dossierId = $request->query->get('dossier');
        $dossier = null;
        if ($dossierId) {$dossier = $this->dossierService->getDossier($dossierId);}

        $fragment = $request->query->get('fragment', 'link-Acceuil');
        $dossiersArray = $user->getDossiers()->toArray();
        $filteredDossiers = $this->filterDossiersByService($fragment, $dossiersArray);

        $viewData = array_merge($userData, [
            'currentFragment' => $fragment,
            'filteredDossiers' => $filteredDossiers,
            'dossier' => $dossier,
            'events' => $events,
            'addDossier' => $dossierForm->createView(),
            'addFacture' => $factureForm,
            'addDevis' => $devisForm,
            'addService' => $serviceForm,
            'addDocument' => $documentForm,
            'addRepertoire' => $repertoireForm,
            'addTypeDocument' => $typeDocumentForm,
        ]);
        
        if ($request->isXmlHttpRequest()) {
            return $this->render('userPage/_fragmentContent.html.twig', $viewData);
        }
        
        return $this->render('userPage/user.html.twig', $viewData);
    }

    private function filterDossiersByService(?string $fragment, array $dossiers): array
    {
        $serviceMapping = [
            'link-Administratif' => ['Administratif'],
            'link-newDocument' => ['Administratif'],
            'link-Repertoire' => ['Repertoire'],
            'link-PageRepertoire' => ['Repertoire'],
            'link-espacepersonnel' => ['Repertoire'],
            'link-Profile' => ['Espace Personnel'],
            'link-Factures' => ['Espace Personnel'],
            'link-PageFacture' => ['Espace Personnel'],
            'link-PageDevis' => ['Espace Personnel'],
            'link-parametres' => ['Espace Personnel'],
            'link-Agenda' => ['Agenda'],
            'link-MainAgenda' => ['Agenda'],
            'link-PageAgenda' => ['Agenda'],
            'link-Commercial' => ['Commercial'],
            'link-Numerique' => ['Numérique'],
            'link-Telephone' => ['Téléphonique']
        ];
    
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
