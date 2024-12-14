<?php
namespace App\Service;

use App\Entity\Dossier;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DossierService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addDossier(Dossier $dossier, User $user)
    {
        $existingDossier = $this->em
            ->getRepository(Dossier::class)
            ->findOneBy(['name' => $dossier->getName(), 'user' => $user]);

        if ($existingDossier) {
            return false;
        }

        $user->addDossier($dossier);
        $this->em->persist($dossier);
        $this->em->flush();

        return true;
    }
}
