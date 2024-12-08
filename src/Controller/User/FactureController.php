<?php
// src/Controller/FactureController.php

namespace App\Controller\User;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class FactureController extends AbstractController
{
    private $factureRepository;

    public function __construct(FactureRepository $factureRepository)
    {
        $this->factureRepository = $factureRepository;
    }

    #[Route('/factures', name: 'list_factures')]
    public function list(): Response
    {
        $factures = $this->factureRepository->findByClient($this->getUser());
        return $this->render('facture/list.html.twig', [
            'factures' => $factures,
        ]);
    }

    #[Route('/facture/{id}', name: 'show_facture')]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/facture/create', name: 'create_facture')]
    public function create(Request $request): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facture->setClient($this->getUser());
            $this->getDoctrine()->getManager()->persist($facture);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('list_factures');
        }

        return $this->render('facture/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
