<?php

namespace App\DataFixtures;

use App\Entity\Dossier;
use App\Entity\User;
use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DossierFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $userRepository = $manager->getRepository(User::class);
        $servicesRepository = $manager->getRepository(Services::class);

        $services = $servicesRepository->findAll();
        $users = $userRepository->findAll();

        if (count($users) === 0 || count($services) === 0) {
            throw new \Exception("Il faut d'abord charger les fixtures pour User et Services.");
        }

        for ($i = 0; $i < 10; $i++) { 
            $dossier = new Dossier();
            $dossier->setName($faker->word() . ' Dossier');
            
            $dossier->setUser($userRepository->findOneBy(['email' => 'user@example.com']));
            $dossier->setServices($faker->randomElement($services));

            $manager->persist($dossier);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ServicesFixtures::class,
        ];
    }
}
