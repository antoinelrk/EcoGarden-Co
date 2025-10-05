<?php

namespace App\DataFixtures;

use App\Entity\Advice;
use App\Entity\User;
use App\Enums\MonthEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        protected UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);

        for ($i = 1; $i <= 5; $i++) {
            $advice = new Advice();
            $advice->setTitle("Advice Title $i")
                   ->setDescription("This is the description for advice number $i.")
                    ->setMonths(MonthEnum::randomEnum(5)) // Assuming randomEnum returns an array of MonthEnum values
                   ->setCreatedAt(new \DateTimeImmutable())
                   ->setUpdatedAt(new \DateTimeImmutable());
            $manager->persist($advice);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'admin@ecogarden.co',
                'username' => 'administrator',
                'password' => 'P@ss1234',
                'roles' => ['ROLE_ADMIN'],
                'city' => 'Paris'
            ],
            [
                'email' => 'user@ecogarden.co',
                'username' => 'user',
                'password' => 'P@ss1234',
                'roles' => ['ROLE_USER'],
                'city' => 'New York'
            ]
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email'])
                ->setRoles($userData['roles'])
                ->setUsername($userData['username'])
                ->setCity($userData['city'])
                ->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));

            $manager->persist($user);
        }
    }
}
