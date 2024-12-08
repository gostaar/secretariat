<?php

namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Events;
use App\Repository\EventsRepository;


class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;

    public function __construct(EventsRepository $eventsRepository){
        $this->eventsRepository = $eventsRepository;
    }

    private function getUserData(): array
    {
        $user = $this->getUser();
        return [
            'user' => $user,
            'factures' => $user->getFactures(),
            'fichiers' => $user->getFichiers(),
        ];
    }

    #[Route('/user', name: 'user')]
    public function index(EventsRepository $eventsRepository): Response
    {
        $now = new \DateTime(); 
        $events = $eventsRepository->findUpcomingEvents($now);

        return $this->render('user/dashboard.html.twig', array_merge($this->getUserData(), [
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

    #[Route('/user_documents', name: 'user_documents')]
    public function documents(): Response
    {
        return $this->render('user/documents.html.twig', array_merge($this->getUserData(), [
            'currentRoute' => 'user_documents',
        ]));
    }  

    #[Route('/user_factures', name: 'user_factures')]
    public function factures(): Response
    {
        return $this->render('user/factures.html.twig', array_merge($this->getUserData(), [
            'currentRoute' => 'user_factures',
        ]));
    }

    #[Route('/user_profile', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', array_merge($this->getUserData(), [
            'currentRoute' => 'user_profile',
        ]));
    }

}
