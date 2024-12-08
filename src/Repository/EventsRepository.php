<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class EventsRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;
    private $em;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Events::class);
        $this->em = $registry->getManager();
        $this->logger = $logger;;
    }
    
    public function saveEvent(string $title, string $description, string $location, string $start, string $end, string $googleCalendarEventId)
    {
        try {
            $startDateTime = new \DateTime($start);
            $endDateTime = new \DateTime($end);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la conversion des dates.', [
                'start' => $start,
                'end' => $end,
                'exception' => $e->getMessage()
            ]);
            throw new \InvalidArgumentException('Les dates fournies ne sont pas valides.');
        }

        $event = new Events();
        $event->setTitle($title);
        $event->setDescription($description);
        $event->setLocation($location);
        $event->setStart($startDateTime);
        $event->setEnd($endDateTime);
        $event->setGoogleCalendarEventId($googleCalendarEventId);
    
        $this->logger->info('Sauvegarde de l\'événement dans la base de données.');
        $this->em->persist($event);
        $this->em->flush();
        
        return $event;
    }

    public function findUpcomingEvents(\DateTime $now)
    {
        return $this->createQueryBuilder('e')
            ->where('e.start >= :now')  // Filtrer par la date de début de l'événement
            ->setParameter('now', $now)
            ->orderBy('e.start', 'ASC')  // Trier par date de début
            ->getQuery()
            ->getResult();
    }
}
