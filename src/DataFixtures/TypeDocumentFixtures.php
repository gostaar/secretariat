<?php 
namespace App\DataFixtures;

use App\Entity\TypeDocument;
use App\Entity\DocumentsUtilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TypeDocumentFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $typeDocument1 = new TypeDocument();
        $typeDocument1->setName('Type Document 1');
        $manager->persist($typeDocument1);

        $typeDocument2 = new TypeDocument();
        $typeDocument2->setName('Type Document 2');
        $manager->persist($typeDocument2);

        $typeDocument3 = new TypeDocument();
        $typeDocument3->setName('Type Document 3');
        $manager->persist($typeDocument3);

        $manager->flush();
    }

}
