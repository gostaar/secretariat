<?php
namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Repertoire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ContactFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $repertoires = $manager->getRepository(Repertoire::class)->findAll();
        
        if (count($repertoires) === 0) {
            throw new \Exception("Il faut d'abord charger les fixtures pour Repertoire.");
        }

        for ($i = 0; $i < 10; $i++) {
            $contact = new Contact();
            $contact->setNom($faker->name);
            $contact->setTelephone($faker->phoneNumber);
            $contact->setEmail($faker->email);
            $contact->setRole($faker->jobTitle);
            $contact->setCommentaire($faker->sentence);
            $contact->setRepertoire($faker->randomElement($repertoires));

            $manager->persist($contact);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RepertoireFixtures::class, 
        ];
    }
}
