<?php

namespace App\Controller\Api;

use App\Entity\Events;
use App\Repository\EventsRepository;
use App\Service\GoogleCalendarService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class EventsController extends AbstractController
{
    private $googleCalendarService;
    private $eventsRepository;
    private $entityManager;
    private LoggerInterface $logger;

    public function __construct(
        GoogleCalendarService $googleCalendarService,
        EventsRepository $eventsRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->googleCalendarService = $googleCalendarService;
        $this->eventsRepository = $eventsRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    // === Google Calendar Integration ===
    #[Route('/google-calendar/events', name: 'google_events', methods: ['POST'])]
    public function listGoogleEvents(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $accessToken = $data['accessToken'] ?? '';

        try {
            $events = $this->googleCalendarService->listEvents($accessToken);
            return new JsonResponse($events);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/google-calendar/create-event', name: 'google_create_event', methods: ['POST'])]
    public function createGoogleEvent(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $this->logger->info('Request data', ['data' => $data]);

        $accessToken = $data['accessToken'] ?? '';
        $eventData = $data['eventData'] ?? [];

        if (empty($accessToken) || empty($eventData)) {
            return new JsonResponse(['error' => 'Access token or event data is missing'], 400);
        }

        try {
            $event = $this->googleCalendarService->createEvent($accessToken, $eventData);
            return new JsonResponse($event);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    // === Events Management ===
    public function addEvent(EntityManagerInterface $entityManager, string $eventName)
    {
        $eventRepository = $entityManager->getRepository(Event::class);
    
        $existingEvent = $eventRepository->findOneBy(['name' => $eventName]);
    
        if ($existingEvent) {
            return "Cet événement existe déjà : " . $existingEvent->getName();
        }
    
        $newEvent = new Event();
        $newEvent->setName($eventName);
    
        $entityManager->persist($newEvent);
        $entityManager->flush();
    
        return "Événement ajouté avec succès.";
    }

    #[Route('/events_byNow', name: 'get_events_by_now', methods: ['GET'])]
    public function getEventsByNow(): JsonResponse
    {
        $now = new \DateTime(); 
        $events = $this->eventsRepository->findUpcomingEvents($now);
        return new JsonResponse($this->formatEvents($events));
    }

    #[Route('/create_events', name: 'create_event', methods: ['POST'])]
    public function createEvent(Request $request, LoggerInterface $logger): JsonResponse
    {
        
        $data = json_decode($request->getContent(), true);
        $logger->info('Data received: ' . json_encode($data)); // Affichez le contenu pour le débogage

        if (empty($data)) {
            return new JsonResponse(['error' => 'Event data is missing'], 400);
        }

        $createdEvents = [];
        $skippedEvents = [];
        
        foreach ($data as $eventData) {
            try {
                $existingEvent = $this->eventsRepository->findOneBy([
                    'title' => $eventData['title'],
                    'location' => $eventData['location'],
                    'start' => new \DateTime($eventData['start']),
                    'end' => new \DateTime($eventData['end']),
                ]);
    
                if ($existingEvent) {
                    $skippedEvents[] = $eventData['title'];
                    continue;
                }
    
                $event = $this->eventsRepository->saveEvent(
                    $eventData['title'],
                    $eventData['description'],
                    $eventData['location'],
                    $eventData['start'],
                    $eventData['end'],
                    $eventData['googleCalendarEventId'] ?? null
                );
    
                $createdEvents[] = $eventData['title'];
    
            } catch (\Exception $e) {
                $logger->error('Failed to create event: ' . $e->getMessage());
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
        }
    
        return new JsonResponse([
            'success' => 'Events processed successfully',
            'created' => $createdEvents,
            'skipped' => $skippedEvents,
        ]);
    }

    #[Route('/events', name: 'get_events', methods: ['GET'])]
    public function getAllEvents(): JsonResponse
    {
        $events = $this->eventsRepository->findAll();
        return new JsonResponse($this->formatEvents($events));
    }

    #[Route('/event/{id}', name: 'get_event', methods: ['GET'])]
    public function getEvent(int $id, SerializerInterface $serializer): JsonResponse
    {
        $event = $this->eventsRepository->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }

        $data = $serializer->serialize($event, 'json', ['groups' => 'event:read']);
        return new JsonResponse($data, 200, [], true);
    }

    private function formatEvents(array $events): array
    {
        return array_map(function ($event) {
            return [
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'location' => $event->getLocation(),
                'start' => $event->getStart()->format('Y-m-d H:i'),
                'end' => $event->getEnd()->format('Y-m-d H:i'),
                'googleCalendarEventId' => $event->getGoogleCalendarEventId() ?? null,
            ];
        }, $events);
    }
}