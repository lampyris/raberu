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
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setName("User $i");
            $manager->persist($user);
            $users[] = $user;

            if ($i <= 3) {
                $ordersCount = rand(5, 10);
            } elseif ($i <= 7) {
                $ordersCount = rand(2, 5);
            } else {
                $ordersCount = rand(0, 2);
            }

            for ($j = 0; $j < $ordersCount; $j++) {
                $order = new Order();
                $order->setUser($user);

                if ($i <= 3) {
                    $amount = rand(5000, 15000);
                } elseif ($i <= 7) {
                    $amount = rand(1000, 5000);
                } else {
                    $amount = rand(100, 1000);
                }

                $order->setAmount($amount);
                $order->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($order);
            }
        }

        for ($i = 1; $i <= 3; $i++) {
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

        echo "Created " . count($users) . " users with different order amounts\n";
        echo "Created 4 vacancies (3 active, 1 inactive)\n";
    }
}
