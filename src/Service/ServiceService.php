<?php
namespace App\Service;

use App\Entity\Services;
use App\Entity\DocumentsUtilisateur;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ServiceService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addServices(Services $service, User $user)
    {
        $existingServices = $this->em
            ->getRepository(Services::class)
            ->findOneBy(['name' => $service->getName(), 'user' => $user]);

        if ($existingServices) {
            return false;
        }

        $user->addServices($service);
        $this->em->persist($service);
        $this->em->flush();

        return true;
    }

    public function deleteService(int $id, User $user)
    {
        $existingService = $this->em
            ->getRepository(Service::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (!$existingService) {
            return false; 
        }

        $this->em->remove($existingService);
        $this->em->flush();

        return true; 
    }

    public function updateService(
        int $id, 
        User $user, 
        ?string $name, 
        ?DocumentsUtilisateur $documentsUtilisateurs, 
    ){
        $existingService = $this->em
            ->getRepository(Service::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (!$existingService) {
            return false; 
        }

        if ($name !== null) {
            $existingService->setName($name);
            $existingService->addDocumentsUtilisateur($documentsUtilisateurs);
        }

        $this->em->flush();

        return true; 
    }
}