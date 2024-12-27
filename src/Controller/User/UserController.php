<?php
namespace App\Controller\User;

use App\Service\RouteDataService;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    private EventsRepository $eventsRepository;
    private RouteDataService $routeDataService;

    public function __construct(
        EventsRepository $eventsRepository, 
        RouteDataService $routeDataService
    ){
        $this->eventsRepository = $eventsRepository;
        $this->routeDataService = $routeDataService;
    }

    #[Route('/user', name: 'user')]
    public function index(Request $request): Response
    {
        $fragment = $request->query->get('fragment', "link-Acceuil");
        $formData = $this->routeDataService->getFormData($fragment);

        return $this->render('userPage/user.html.twig', $formData);
    }

    #[Route('/changefragment', name: 'changefragment')]
    public function changefragment(Request $request): Response
    {
        $fragment = $request->query->get('fragment', "link-Acceuil");
        $formData = $this->routeDataService->getFormData($fragment);

        return $this->json([
            'fragmentContent' => $this->renderView('userPage/_fragmentContent.html.twig', $formData),
        ]);
    }

    #[Route('/user_agenda', name: 'user_agenda', methods: ['GET'])]
    public function agenda(Request $request): Response
    {
        $now = new \DateTime(); 
        $events = $this->eventsRepository->findUpcomingEvents($now);

        $userData = $this->getUserData();
        
        return $this->render('user/agenda.html.twig', array_merge($userData, [
            'currentRoute' => 'user_agenda',
            'events' => $events,
        ]));
    }

    private function getUserData(): array
    {
        $user = $this->getUser();
        return [
            'user' => $user,
            'services' => $user->getServices(),
        ];
    }
}
