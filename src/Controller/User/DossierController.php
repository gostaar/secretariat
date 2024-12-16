<?php

namespace App\Controller\User;
use App\Entity\Dossier;
use App\Form\DossierType;
use App\Service\DossierService;
use App\Entity\DocumentsUtilisateur;
use App\Form\DocumentsUtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\TypeDocument;
use App\Form\TypeDocumentType;

class DossierController extends AbstractController
{
    private EntityManagerInterface $em;
    private DossierService $dossierService;
    
    public function __construct(EntityManagerInterface $em, DossierService $dossierService){
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
            'services' => $user->getServices(),
            'repertoire' => $user->getRepertoires(),
            'documents' => $user->getDocuments(),
            'dossiers' => $user->getDossiers(),
        ];
    }

    #[Route('/dossier/{id}', name: 'dossier', methods: ['GET', 'POST'])]
    public function getDossier($id)
    {
        $dossier = $this->dossierService->getDossier($id);
        $documents = $dossier->getDocumentsUtilisateurs();

        $documentForm = $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur(), [
            'dossier' => $dossier,
        ]);
        $typeDocumentForm = $this->createForm(TypeDocumentType::class, new TypeDocument());

        return $this->render('userPage/dossier.html.twig', [
            'dossier' => $dossier,
            'documents' => $documents,
            'addDocument' => $documentForm,
            'addTypeDocument' => $typeDocumentForm
        ]);
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

}