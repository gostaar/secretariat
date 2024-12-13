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

class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;

    public function __construct(EventsRepository $eventsRepository){
        $this->eventsRepository = $eventsRepository;
    }

    private function getUserData(): array
    {
        $user = $this->getUser();
    
        // Créer un objet Facture et son formulaire
        $facture = new Facture();
        $facture->setStatus(FactureStatus::NON_PAYE->value);
        $factureForm = $this->createForm(FactureType::class, $facture);
        
        // Traitement du formulaire Facture
        if ($factureForm->isSubmitted() && $factureForm->isValid()) {
            $user->addFacture($facture);
            $this->getDoctrine()->getManager()->persist($facture);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Facture ajoutée avec succès.');
        }
    
        // Créer un objet Devis et son formulaire
        $devis = new Devis();
        $devis->setStatus(DevisStatus::EN_ATTENTE->value);;
        $devisForm = $this->createForm(DevisType::class, $devis);
    
        // Traitement du formulaire Devis
        if ($devisForm->isSubmitted() && $devisForm->isValid()) {
            $user->addDevis($devis);
            $this->getDoctrine()->getManager()->persist($devis);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Devis ajouté avec succès.');
        }
    
        // Créer un objet Service et son formulaire
        $service = new Services();
        $serviceForm = $this->createForm(ServicesType::class, $service);
    
        // Traitement du formulaire Service
        if ($serviceForm->isSubmitted() && $serviceForm->isValid()) {
            $user->addService($service);
            $this->getDoctrine()->getManager()->persist($service);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Service ajouté avec succès.');
        }
    
        // Créer un objet Document et son formulaire
        $document = new DocumentsUtilisateur();
        $documentForm = $this->createForm(DocumentsUtilisateurType::class, $document);
    
        // Traitement du formulaire Document
        if ($documentForm->isSubmitted() && $documentForm->isValid()) {
            $user->addDocumentUtilisateur($document);
            $this->getDoctrine()->getManager()->persist($document);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Document ajouté avec succès.');
        }

        // Créer un objet Document et son formulaire
        $repertoire = new Repertoire();
        $repertoireForm = $this->createForm(RepertoireType::class, $repertoire);
    
        // Traitement du formulaire repertoire
        if ($repertoireForm->isSubmitted() && $repertoireForm->isValid()) {
            $user->addrepertoireUtilisateur($repertoire);
            $this->getDoctrine()->getManager()->persist($repertoire);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'repertoire ajouté avec succès.');
        }
    
        return [
            'user' => $user,
            'factures' => $user->getFactures(),
            'devis' => $user->getDevis(),
            'services' => $user->getServicesSouscrits(),
            'repertoire' => $user->getRepertoires(),
            'documents' => $user->getDocumentsUtilisateurs(),
            'addFacture' => $factureForm->createView(),
            'addDevis' => $devisForm->createView(),
            'addService' => $serviceForm->createView(),
            'addDocument' => $documentForm->createView(),
            'addRepertoire' => $repertoireForm->createView(),
        ];
    }

    #[Route('/user', name: 'user')]
    public function index(EventsRepository $eventsRepository): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        return $this->render('pages/user.html.twig', array_merge($this->getUserData(), [
            'currentRoute' => 'user',
            'events' => $events
        ]));
    }

    #[Route('/user_agenda', name: 'user_agenda')]
    public function agenda(EventsRepository $eventsRepository): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        return $this->render('user/agenda.html.twig', array_merge($this->getUserData(), [
            'currentRoute' => 'user_agenda',
            'events' => $events 
        ]));
    }

}
