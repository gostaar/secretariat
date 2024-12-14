<?php

namespace App\Controller\User;

use App\Entity\Devis;
use App\Entity\DocumentsUtilisateur;
use App\Entity\Dossier;
use App\Entity\Events;
use App\Entity\Facture;
use App\Entity\Repertoire;
use App\Entity\Services;
use App\Entity\User;
use App\Form\DevisType;
use App\Form\DocumentsUtilisateurType;
use App\Form\DossierType;
use App\Form\FactureType;
use App\Form\RepertoireType;
use App\Form\ServicesType;
use App\Service\DevisService;
use App\Service\DocumentService;
use App\Service\DossierService;
use App\Service\FactureService;
use App\Service\RepertoireService;
use App\Service\ServiceService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EventsRepository;
use App\Enum\DevisStatus;
use App\Enum\FactureStatus;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private EntityManagerInterface $em;
    private DevisService $devisService;
    private DossierService $dossierService;
    private DocumentService $documentService;
    private FactureService $factureService;
    private RepertoireService $repertoireService;
    private ServiceService $serviceService;
    
    public function __construct(
        EventsRepository $eventsRepository, 
        EntityManagerInterface $em, 
        DevisService $devisService,
        DossierService $dossierService,
        DocumentService $documentService,
        FactureService $factureService,
        RepertoireService $repertoireService,
        ServiceService $serviceService,
    ){
        $this->eventsRepository = $eventsRepository;
        $this->em = $em;
        $this->devisService = $devisService;
        $this->dossierService = $dossierService;
        $this->documentService = $documentService;
        $this->factureService = $factureService;
        $this->repertoireService = $repertoireService;
        $this->serviceService = $serviceService;
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
        
        return $this->render('pages/user.html.twig', array_merge($userData, [
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
        
    #[Route('/add_dossier', name: 'add_dossier', methods: ['POST'])]
    public function addDossier(Request $request): Response
    {
        $user = $this->getUser();
        $dossier = new Dossier();
        $dossierForm = $this->createForm(DossierType::class, $dossier);
        
        $dossierForm->handleRequest($request);
        
        if ($dossierForm->isSubmitted() && $dossierForm->isValid()) {
            $this->dossierService->addDossier($dossier, $user);
            
            $url = $this->generateUrl('user') . '#link-Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_dossier/{id}', name: 'update_dossier', methods: ['POST'])]
    public function updateDossier(int $id, Request $request) : JsonResponse
    {
        $name = $request->get('name');

        $user = $this->getUser();
        $this->dossierService->updateDossier($id, $user, $name);
        return new JsonResponse(['success' => true], 200);
    }
    
    #[Route('/delete_dossier/{id}', name: 'delete_dossier', methods: ['DELETE'])]
    public function deleteDossier(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->dossierService->deleteDossier($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/add_facture', name: 'add_facture', methods: ['POST'])]
    public function addFacture(Request $request): Response
    {
        $user = $this->getUser();
        $facture = new Facture();
        $factureForm = $this->createForm(FactureType::class, $facture);
    
        $factureForm->handleRequest($request);

        if ($factureForm->isSubmitted() && $factureForm->isValid()) {
            $this->factureService->addFacture($facture, $user);
    
            $url = $this->generateUrl('user') . '#link-Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_facture/{id}', name: 'update_facture', methods: ['POST'])]
    public function updateFacture(int $id, Request $request) : JsonResponse
    {
        $montant = $request->get('montant');
        $date_facture = $request->get('date_facture');
        $date_paiement = $request->get('date_paiement');
        $status = $request->get('status');
        $commentaire = $request->get('commentaire');
        $is_active = $request->get('is_active');
        
        $user = $this->getUser();
        $this->factureService->updateFacture($id, $user, $montant, $date_facture, $date_paiement, $status, $commentaire, $is_active);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/delete_facture/{id}', name: 'delete_facture', methods: ['DELETE'])]
    public function deleteFacture(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->factureService->deleteFacture($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/add_devis', name: 'add_devis', methods: ['POST'])]
    public function addDevis(Request $request): Response
    {
        $user = $this->getUser();
        $devis = new Devis();
        $devisForm = $this->createForm(DevisType::class, $devis);
    
        $devisForm->handleRequest($request);

        if ($devisForm->isSubmitted() && $devisForm->isValid()) {
            $this->devisService->addDevis($devis, $user);
    
            $url = $this->generateUrl('user') . '#link-Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_devis/{id}', name: 'update_devis', methods: ['POST'])]
    public function updateDevis(int $id, Request $request) : JsonResponse
    {
        $montant = $request->get('montant');
        $date_devis = $request->get('date_devis');
        $commentaire = $request->get('commentaire');
        $is_active = $request->get('is_active');
        $status = $request->get('status');
        
        $user = $this->getUser();
        $this->devisService->updateDevis($id, $user, $montant, $date_devis, $commentaire, $is_active, $status);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/delete_devis/{id}', name: 'delete_devis', methods: ['DELETE'])]
    public function deleteDevis(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->devisService->deleteDevis($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/add_service', name: 'add_service', methods: ['POST'])]
    public function addService(Request $request): Response
    {
        $user = $this->getUser();
        $service = new Service();
        $serviceForm = $this->createForm(ServiceType::class, $service);
    
        $serviceForm->handleRequest($request);
        
        if ($serviceForm->isSubmitted() && $serviceForm->isValid()) {
            $this->serviceService->addService($service, $user);
    
            $url = $this->generateUrl('user') . '#link-Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_service/{id}', name: 'update_service', methods: ['POST'])]
    public function updateService(int $id, Request $request) : JsonResponse
    {
        $name = $request->get('name');
        $documentsUtilisateurs = $request->get('documentsUtilisateurs');
        
        $user = $this->getUser();
        $this->serviceService->updateService($id, $user, $name, $documentsUtilisateurs);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/delete_service/{id}', name: 'delete_service', methods: ['DELETE'])]
    public function deleteService(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->serviceService->deleteService($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/add_document', name: 'add_document', methods: ['POST'])]
    public function addDocument(Request $request): Response
    {
        $user = $this->getUser();

        $document = new DocumentsUtilisateur();
        $documentForm = $this->createForm(DocumentsUtilisateurType::class, $document);
        $documentForm->handleRequest($request);
        
        if ($documentForm->isSubmitted() && $documentForm->isValid()) {
            $this->documentService->addService($document, $user);
            
            $url = $this->generateUrl('user') . '#link-Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }
    
    #[Route('/update_document/{id}', name: 'update_document', methods: ['POST'])]
    public function updateDocument(int $id, Request $request) : JsonResponse
    {
        $type_document = $request->get('type_document');
        $date_document = $request->get('date_document');
        $dossier = $request->get('dossier');
        $details = $request->get('details');
        $expediteur = $request->get('expediteur');
        $destinataire = $request->get('destinataire');
        $service = $request->get('service');
        $file_path = $request->get('file_path');
        $is_active = $request->get('is_active');
        
        $user = $this->getUser();
        $this->documentService->updateDocument($id, $user, $type_document, $date_document, $dossier, $details, $expediteur, $destinataire, $service, $file_path, $is_active);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/delete_document/{id}', name: 'delete_document', methods: ['DELETE'])]
    public function deleteDocument(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->documentService->deleteDocument($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/add_repertoire', name: 'add_repertoire', methods: ['POST'])]
    public function addRepertoire(Request $request): Response
    {
        $user = $this->getUser();

        $repertoire = new Repertoire();
        $repertoireForm = $this->createForm(RepertoireType::class, $repertoire);
        $repertoireForm->handleRequest($request);
        
        if ($repertoireForm->isSubmitted() && $repertoireForm->isValid()) {
            $this->repertoireService->addService($repertoire, $user);
    
            $url = $this->generateUrl('user') . '#link-Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_repertoire/{id}', name: 'update_repertoire', methods: ['POST'])]
    public function updateRepertoire(int $id, Request $request) : JsonResponse
    {
        $nom = $request->get('nom');
        $adresse = $request->get('adresse');
        $code_postal = $request->get('code_postal');
        $ville = $request->get('ville');
        $pays = $request->get('pays');
        $telephone = $request->get('telephone');
        $mobile = $request->get('mobile');
        $email = $request->get('email');
        $siret = $request->get('siret');
        $nom_entreprise = $request->get('nom_entreprise');
        $dossier = $request->get('dossier');
        $contact = $request->get('contact');
        
        $user = $this->getUser();
        $this->repertoireService->updateRepertoire($id, $user, $nom, $adresse, $code_postal, $ville, $pays, $telephone, $mobile, $email, $siret, $nom_entreprise, $dossier, $contact);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/delete_repertoire/{id}', name: 'delete_repertoire', methods: ['DELETE'])]
    public function deleteRepertoire(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->repertoireService->deleteRepertoire($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/user_agenda', name: 'user_agenda', methods: ['POST'])]
    public function agenda(EventsRepository $eventsRepository, Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        $user = $this->getUser();
        $this->handleForms(
            $request, 
            $user
        );
        $userData = $this->getUserData();
        $formViews = $this->createFormViews();

        return $this->render('user/agenda.html.twig', array_merge($userData, $formViews, [
            'currentRoute' => 'user_agenda',
            'events' => $events 
        ]));
    }

}
