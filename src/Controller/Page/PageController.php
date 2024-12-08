<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function index(): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('app_logout'));
        }

        return $this->render('pages/index.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }

    #[Route('/jobs', name: 'jobs')]
    public function jobs(): Response
    {
        return $this->render('pages/jobs.html.twig');
    }

    #[Route('/numeriques', name: 'numeriques')]
    public function numeriques(): Response
    {
        return $this->render('pages/numerique.html.twig');
    }

    #[Route('/secretariat', name: 'secretariat')]
    public function secretariat(): Response
    {
        return $this->render('pages/secretariat.html.twig');
    }
}
