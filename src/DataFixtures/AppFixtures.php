<?php

namespace App\DataFixtures;

use App\Entity\Advice;
use App\Enums\MonthEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
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
}
