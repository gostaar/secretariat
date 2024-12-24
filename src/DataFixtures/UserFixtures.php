<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as FakerFactory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword('$2y$13$/07W5XdhdJOm2LZypCHvPe76Ly2W/E.DfsQtA0Q0rhbVTwz4stkyC'); // Mot de passe déjà haché
        $user->setRoles(['ROLE_USER']);
        $user->setNom($faker->name);
        $user->setAdresse($faker->address);
        $user->setCodePostal($faker->postcode);
        $user->setVille($faker->city);
        $user->setPays($faker->country);
        $user->setTelephone($faker->phoneNumber);
        $user->setMobile($faker->phoneNumber);
        $user->setSiret($faker->numberBetween(1000000000, 9999999999));
        $user->setNomEntreprise($faker->company);

        $manager->persist($user);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setNom($faker->name);
        $admin->setAdresse($faker->address);
        $admin->setCodePostal($faker->postcode);
        $admin->setVille($faker->city);
        $admin->setPays($faker->country);
        $admin->setTelephone($faker->phoneNumber);
        $admin->setMobile($faker->phoneNumber);
        $admin->setSiret($faker->numberBetween(1000000000, 9999999999));
        $admin->setNomEntreprise($faker->company);

        $manager->persist($admin);

        $manager->flush();
    }
}
