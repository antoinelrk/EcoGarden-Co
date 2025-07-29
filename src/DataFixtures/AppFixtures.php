<?php

namespace App\DataFixtures;

use App\Entity\Conseil;
use App\Enums\MonthEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        for ($i = 1; $i <= 100; $i++) {
//            $advice = new Conseil();
//            $advices[] = [
//                'advice' => "Advice number $i",
//                'months' => MonthEnum::randomEnum(5)
//            ];
//        }

        $manager->flush();
    }
}
