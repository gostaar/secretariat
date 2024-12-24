<?php
namespace App\Service;

use App\Entity\Devis;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DevisService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getDevis($id)
    {
        return $this->em->getRepository(Devis::class)->find($id);
    }

    public function addDevis(Devis $devis, User $user)
    {
        $existingDevis = $this->em
            ->getRepository(Devis::class)
            ->findOneBy(['id' => $devis->getId(), 'client' => $user]);

        if ($existingDevis) {
            return false;
        }

        $user->addDevis($devis);
        $this->em->persist($devis);
        $this->em->flush();

        return true;
    }

    public function deleteDevis(int $id, User $user)
    {
        $existingDevis = $this->em
            ->getRepository(Devis::class)
            ->findOneBy(['id' => $id, 'client' => $user]);

        if (!$existingDevis) {
            return false; 
        }

        $this->em->remove($existingDevis);
        $this->em->flush();

        return true; 
    }

    public function updateDevis(
        int $id, 
        User $user, 
        ?float $montant, 
        ?DateTimeInterface $date_devis, 
        ?string $status, 
        ?string $commentaire, 
        ?bool $is_active
    ){
        $existingDevis = $this->em
            ->getRepository(Devis::class)
            ->findOneBy(['id' => $id, 'client' => $user]);

        if (!$existingDevis) {
            return false; 
        }

        if ($name !== null) {
            $existingDevis->setMontant($montant);
            $existingDevis->setDateDevis($date_devis);
            $existingDevis->setStatus($status);
            $existingDevis->setCommentaire($commentaire);
            $existingDevis->setIsActive($is_active);
        }

        $this->em->flush();

        return true; 
    }
}
