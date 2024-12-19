<?php

namespace App\Controller\User;
use App\Entity\Services;
use App\Form\ServicesType;
use App\Service\ServiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    private EntityManagerInterface $em;
    private ServiceService $serviceService;
   
    public function __construct(EntityManagerInterface $em, ServiceService $serviceService,){
        $this->em = $em;
        $this->serviceService = $serviceService;
    }

    #[Route('/service/{id}', name: 'service', methods: ['GET'])]
    public function getService($id): Response
    {
        $serviceS= $this->serviceService->getService($id);

        return $this->redirectToRoute('user', [
            'service' => $serviceS  
        ], 302, ['fragment' => 'link-PageServices']);
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

}