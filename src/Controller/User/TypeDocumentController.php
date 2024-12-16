<?php

namespace App\Controller\User;
use App\Entity\TypeDocument;
use App\Form\TypeDocumentType;
use App\Service\TypeDocumentService;
use App\Entity\DocumentsUtilisateur;
use App\Form\DocumentsUtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class TypeDocumentController extends AbstractController
{
    private EntityManagerInterface $em;
    private TypeDocumentService $TypeDocumentService;
    
    public function __construct(EntityManagerInterface $em, TypeDocumentService $TypeDocumentService){
        $this->em = $em;
        $this->TypeDocumentService = $TypeDocumentService;
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

    #[Route('/TypeDocument/{id}', name: 'TypeDocument', methods: ['GET'])]
    public function getTypeDocument($id)
    {
        $TypeDocument = $this->TypeDocumentService->getTypeDocument($id);
        $documents = $TypeDocument->getDocumentsUtilisateurs();

        $documentForm = $this->createForm(DocumentsUtilisateurType::class, new DocumentsUtilisateur());

        return $this->render('userPage/TypeDocument.html.twig', [
            'TypeDocument' => $TypeDocument,
            'documents' => $documents,
            'addDocument' => $documentForm,
        ]);
    }
        
    #[Route('/add_TypeDocument/{id}', name: 'add_TypeDocument', methods: ['POST'])]
    public function addTypeDocument(Request $request, $id): Response
    {
        if (!$id) {
            throw new \Exception('ID du dossier non défini');
        }

        $user = $this->getUser();
        $TypeDocument = new TypeDocument();
        $TypeDocumentForm = $this->createForm(TypeDocumentType::class, $TypeDocument);
        
        $TypeDocumentForm->handleRequest($request);

        if ($TypeDocumentForm->isSubmitted() && $TypeDocumentForm->isValid()) {
            $this->TypeDocumentService->addTypeDocument($TypeDocument, $user);
    
            $url = $this->generateUrl('dossier', ['id' => $id]);
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_TypeDocument/{id}', name: 'update_TypeDocument', methods: ['POST'])]
    public function updateTypeDocument(int $id, Request $request) : JsonResponse
    {
        $name = $request->get('name');

        $user = $this->getUser();
        $this->TypeDocumentService->updateTypeDocument($id, $user, $name);
        return new JsonResponse(['success' => true], 200);
    }
    
    #[Route('/delete_TypeDocument/{id}', name: 'delete_TypeDocument', methods: ['DELETE'])]
    public function deleteTypeDocument(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->TypeDocumentService->deleteTypeDocument($id, $user);
        return new JsonResponse(['success' => true], 200);
    }

}