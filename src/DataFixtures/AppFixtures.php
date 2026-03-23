<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Vacancy;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setName("User $i");
            $manager->persist($user);
            $users[] = $user;

            $ordersCount = rand(2, 4);
            for ($j = 0; $j < $ordersCount; $j++) {
                $order = new Order();
                $order->setUser($user);
                $order->setAmount(rand(1000, 5000));
                $order->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($order);
            }
        }

        for ($i = 1; $i <= 2; $i++) {
            $vacancy = new Vacancy();
            $vacancy->setTitle("Active Vacancy $i");
            $vacancy->setIsActive(true);
            $manager->persist($vacancy);
        }

        $inactiveVacancy = new Vacancy();
        $inactiveVacancy->setTitle("Inactive Vacancy");
        $inactiveVacancy->setIsActive(false);
        $manager->persist($inactiveVacancy);

        $manager->flush();

        echo "Created " . count($users) . " users with orders\n";
        echo "Created 3 vacancies (2 active, 1 inactive)\n";
    }
}
