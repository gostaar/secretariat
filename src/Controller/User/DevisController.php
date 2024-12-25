<?php

namespace App\Controller\User;
use App\Entity\Devis;
use App\Form\DevisType;
use App\Service\DevisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    private EntityManagerInterface $em;
    private DevisService $devisService;
   
    public function __construct(EntityManagerInterface $em, DevisService $devisService,){
        $this->em = $em;
        $this->devisService = $devisService;
    }

    #[Route('/devis/{id}', name: 'devis', methods: ['GET'])]
    public function getDevis($id)
    {
        $devis = $this->devisService->getDevis($id);

        return $this->redirectToRoute('user', [
            'devis' => $devis
        ], 302, ['fragment' => 'link-PageDevis']);
    }

    #[Route('/add_devis', name: 'add_devis', methods: ['POST'])]
    public function addDevis(Request $request): Response
    {
        $user = $this->getUser();
        $devis = new Devis();
    
        $devisForm = $this->createForm(DevisType::class, $devis);
        $devisForm->handleRequest($request);

        $isActive = $devisForm->get('is_active')->getData();
        $devis->setActive($isActive);
    
        if ($devisForm->isSubmitted() && $devisForm->isValid()) {
            $devis->setUser($user);
    
            $this->factureService->addDevis($devis, $user);
    
            foreach ($devis->getLignes() as $ligne) {
                $ligne->setDevis($devis);
                $this->entityManager->persist($ligne);
            }
    
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Devis et lignes ajoutées avec succès.');
            return $this->redirectToRoute('user', ['fragment' => 'link-Devis'], 302);
        }
    
        return new Response;
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
}