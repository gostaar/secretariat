<?php

namespace App\Controller\User;
use App\Entity\DocumentsUtilisateur;
use App\Form\DocumentsUtilisateurType;
use App\Service\DocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DocumentsUtilisateurController extends AbstractController
{
    private EntityManagerInterface $em;
    private DocumentService $documentService;
   
    public function __construct(EntityManagerInterface $em, DocumentService $documentService){
        $this->em = $em;
        $this->documentService = $documentService;
    }

    #[Route('/document/{id}', name: 'document', methods: ['GET'])]
    public function getDocument($id)
    {
        $dossier = $this->dossierService->getDossier($id);

        $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur(), [
            'dossier' => $dossier,
        ]);

        return $this->redirectToRoute('user', [
            'dossier' => $dossier,
        ], 302, ['fragment' => 'link-PageDocument']);
    }

    #[Route('/add_document', name: 'add_document', methods: ['POST'])]
    public function addDocument(Request $request): Response
    {
        $user = $this->getUser();

        $document = new DocumentsUtilisateur();
        $documentForm = $this->createForm(DocumentsUtilisateurType::class, $document);
        $documentForm->handleRequest($request);
        
        $typeDocument = new TypeDocument();
        $typeDocumentForm = $this->createForm(TypeDocumentType::class, $typeDocument);
        $typeDocumentForm->handleRequest($request);
               
        if ($documentForm->isSubmitted() && $documentForm->isValid()) {
            $this->documentService->addService($document, $user);
        }
        
        if ($typeDocumentForm->isSubmitted() && $typeDocumentForm->isValid()) {
            $this->documentService->addService($typeDocument);
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
}