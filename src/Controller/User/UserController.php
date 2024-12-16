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

class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private EntityManagerInterface $em;
    
    public function __construct(
        EventsRepository $eventsRepository, 
        EntityManagerInterface $em
    ){
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
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
            'repertoire' => $user->getRepertoires(),
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
        
        $dossierForm = $this->createForm(DossierType::class, new Dossier());
        $factureForm = $this->createForm(FactureType::class, new Facture());
        $devisForm = $this->createForm(DevisType::class, new Devis());
        $serviceForm = $this->createForm(ServicesType::class, new Services());
        $documentForm = $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur());
        $repertoireForm = $this->createForm(RepertoireType::class,  new Repertoire());
        $userData = $this->getUserData();
        
        return $this->render('userPage/user.html.twig', array_merge($userData, [
            'currentRoute' => 'user',
            'events' => $events,
            'addDossier' => $dossierForm->createView(),
            'addFacture' => $factureForm,
            'addDevis' => $devisForm,
            'addService' => $serviceForm,
            'addDocument' => $documentForm,
            'addRepertoire' => $repertoireForm,
        ]));
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
