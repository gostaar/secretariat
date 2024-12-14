<?php

namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Events;
use App\Repository\EventsRepository;
use App\Form\FactureType;
use App\Form\DevisType;
use App\Form\DocumentsUtilisateurType;
use App\Form\RepertoireType;
use App\Form\ServicesType;
use App\Entity\Facture;
use App\Entity\Devis;
use App\Entity\DocumentsUtilisateur;
use App\Entity\Repertoire;
use App\Entity\Services;
use App\Enum\DevisStatus;
use App\Enum\FactureStatus;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dossier;
use App\Form\DossierType;
use App\Service\DossierService;

class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private EntityManagerInterface $em;
    private DossierService $dossierService;

    public function __construct(
        EventsRepository $eventsRepository, 
        EntityManagerInterface $em, DossierService 
        $dossierService
    ){
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
        $this->dossierService = $dossierService;
    }

    private function getUserData(): array
    {
        $user = $this->getUser();

        return [
            'user' => $user,
            'factures' => $user->getFactures(),
            'devis' => $user->getDevis(),
            'services' => $user->getServicesSouscrits(),
            'repertoire' => $user->getRepertoires(),
            'documents' => $user->getDocumentsUtilisateurs(),
            'dossiers' => $user->getDossier(),
        ];
    }
    
    private function handleForms(
        Request $request, 
        $user,
        DossierService $dossierService)
    {
        $redirect = false;
        // Créer un objet Facture et son formulaire
        $facture = new Facture();
        $facture->setStatus(FactureStatus::NON_PAYE->value);
        $factureForm = $this->createForm(FactureType::class, $facture);
        $factureForm->handleRequest($request);

        // Traitement du formulaire Facture
        if ($factureForm->isSubmitted() && $factureForm->isValid()) {
            $user->addFacture($facture);
            $this->em->persist($facture);
            $this->em->flush();
            $this->addFlash('success', 'Facture ajoutée avec succès.');
        }
    
        // Créer un objet Devis et son formulaire
        $devis = new Devis();
        $devis->setStatus(DevisStatus::EN_ATTENTE->value);;
        $devisForm = $this->createForm(DevisType::class, $devis);
        $devisForm->handleRequest($request);

        // Traitement du formulaire Devis
        if ($devisForm->isSubmitted() && $devisForm->isValid()) {
            $user->addDevi($devis);
            $this->em->persist($devis);
            $this->em->flush();
            $this->addFlash('success', 'Devis ajouté avec succès.');
        }
    
        // Créer un objet Service et son formulaire
        $service = new Services();
        $serviceForm = $this->createForm(ServicesType::class, $service);
        $serviceForm->handleRequest($request);

        // Traitement du formulaire Service
        if ($serviceForm->isSubmitted() && $serviceForm->isValid()) {
            $user->addServicesSouscrit($service);
            $this->em->persist($service);
            $this->em->flush();
            $this->addFlash('success', 'Service ajouté avec succès.');
        }
    
        // Créer un objet Document et son formulaire
        $document = new DocumentsUtilisateur();
        $documentForm = $this->createForm(DocumentsUtilisateurType::class, $document);
        $documentForm->handleRequest($request);

        // Traitement du formulaire Document
        if ($documentForm->isSubmitted() && $documentForm->isValid()) {
            $user->addDocumentsUtilisateur($document);
            $this->em->persist($document);
            $this->em->flush();
            $this->addFlash('success', 'Document ajouté avec succès.');
        }

        // Créer un objet Document et son formulaire
        $repertoire = new Repertoire();
        $repertoireForm = $this->createForm(RepertoireType::class, $repertoire);
        $repertoireForm->handleRequest($request);

        // Traitement du formulaire repertoire
        if ($repertoireForm->isSubmitted() && $repertoireForm->isValid()) {
            $user->addRepertoire($repertoire);
            $this->em->persist($repertoire);
            $this->em->flush();
            $this->addFlash('success', 'repertoire ajouté avec succès.');
        }
        
        // Créer un objet Dossier et son formulaire
        $dossier = new Dossier();
        $dossierForm = $this->createForm(DossierType::class, $dossier);
        $dossierForm->handleRequest($request);
        
        // Traitement du formulaire repertoire
        if ($dossierForm->isSubmitted() && $dossierForm->isValid()) {
            $success = $this->dossierService->addDossier($dossier, $user);

            if($success){
                return $this->redirectToRoute('success', ['ancre' => 'link-Repertoire']);

            }else {
                $this->addFlash('error', 'Un dossier avec ce nom existe déjà.');
            }    
        }
    }

    #[Route('/success', name: 'success')]
    public function destination(Request $request): Response
    {
        $ancre = $request->query->get('ancre', 'default'); // Fetching the anchor from query string
        return $this->redirectToRoute('user', [], 301, '#' . $ancre);
    }

    private function createFormViews(): array
    {
        return [
            'addFacture' => $this->createForm(FactureType::class, new Facture())->createView(),
            'addDevis' => $this->createForm(DevisType::class, new Devis())->createView(),
            'addService' => $this->createForm(ServicesType::class, new Services())->createView(),
            'addDocument' => $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur())->createView(),
            'addRepertoire' => $this->createForm(RepertoireType::class, new Repertoire())->createView(),
            'addDossier' => $this->createForm(DossierType::class, new Dossier())->createView(),
        ];
    }

    #[Route('/user', name: 'user')]
    public function index(EventsRepository $eventsRepository, Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        $user = $this->getUser();
        // $this->handleForms(
        //     $request, 
        //     $user
        // );
        $userData = $this->getUserData();

        $dossier = new Dossier();
        $dossierForm = $this->createForm(DossierType::class, $dossier);
        $dossierForm->handleRequest($request);
        
        // Traitement du formulaire repertoire
        if ($dossierForm->isSubmitted() && $dossierForm->isValid()) {
            $success = $this->dossierService->addDossier($dossier, $user);

            if($success){
                return $this->redirectToRoute('success', ['ancre' => 'link-Repertoire']);

            }else {
                $this->addFlash('error', 'Un dossier avec ce nom existe déjà.');
            }    
        }
        //$formViews = $this->createFormViews();

        return $this->render('pages/user.html.twig', array_merge($userData,  [
            'currentRoute' => 'user',
            'events' => $events,
            'dossierForm' => $dossierForm //là çca marche
        ]));
    }

    #[Route('/user_agenda', name: 'user_agenda')]
    public function agenda(EventsRepository $eventsRepository, Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        $user = $this->getUser();
        $this->handleForms(
            $request, 
            $user,
            $this->dossierService
        );
        $userData = $this->getUserData();
        $formViews = $this->createFormViews();

        return $this->render('user/agenda.html.twig', array_merge($userData, $formViews, [
            'currentRoute' => 'user_agenda',
            'events' => $events 
        ]));
    }

}
