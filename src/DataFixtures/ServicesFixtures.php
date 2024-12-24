<?php
namespace App\DataFixtures;

use App\Entity\Services;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ServicesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);
        foreach (Services::AVAILABLE_SERVICES as $serviceName) {
            $service = new Services();
            $service->setName($serviceName);
    
            $user = $userRepository->findOneBy(['email' => 'user@example.com']);
            if ($user !== null) {
                $service->addUser($user);
            }
    
            $manager->persist($service);
        }
    
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class, 
        ];
    }
}
