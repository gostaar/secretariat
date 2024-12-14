<?php
namespace App\Service;

use App\Entity\Facture;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FactureService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addFacture(Facture $facture, User $user)
    {
        $existingFacture = $this->em
            ->getRepository(Facture::class)
            ->findOneBy(['id' => $facture->getId(), 'user' => $user]);

        if ($existingFacture) {
            return false;
        }

        $user->addFacture($facture);
        $this->em->persist($facture);
        $this->em->flush();

        return true;
    }

    public function deleteFacture(int $id, User $user)
    {
        $existingFacture = $this->em
            ->getRepository(Facture::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (!$existingFacture) {
            return false; 
        }

        $this->em->remove($existingFacture);
        $this->em->flush();

        return true; 
    }

    public function updateFacture(
        int $id, 
        User $user, 
        ?float $montant, 
        ?DateTimeInterface $date_facture, 
        ?DateTimeInterface $date_paiement, 
        ?string $status, 
        ?string $commentaire, 
        ?bool $is_active
    ){
        $existingFacture = $this->em
            ->getRepository(Facture::class)
            ->findOneBy(['id' => $id, 'user' => $user]);

        if (!$existingFacture) {
            return false; 
        }

        if ($name !== null) {
            $existingFacture->setMontant($montant);
            $existingFacture->setDatePaiement($date_facture);
            $existingFacture->setDateFacture($date_paiement);
            $existingFacture->setStatus($status);
            $existingFacture->setCommentaire($commentaire);
            $existingFacture->setActive($is_active);
        }

        $this->em->flush();

        return true; 
    }
}