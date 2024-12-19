<?php

namespace App\Controller\User;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Service\FactureService;
use App\Enum\FactureStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    private EntityManagerInterface $em;
    private FactureService $factureService;

    public function __construct(EntityManagerInterface $em, FactureService $factureService){
        $this->em = $em;
        $this->factureService = $factureService;
    }

    #[Route('/facture/{id}', name: 'facture', methods: ['GET'])]
    public function getFacture($id)
    {
        $facture = $this->factureService->getFacture($id);
        return $this->redirectToRoute('user', [
            'facture' => $facture
        ], 302, ['fragment' => 'link-PageFacture']);
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
}