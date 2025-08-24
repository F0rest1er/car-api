<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Car;
use App\Entity\CreditProgram;
use App\Entity\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $om): void
    {
        $faker = Factory::create('ru_RU');

        $brands = [];
        $modelsByBrand = [];

        $brandData = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4'],
            'BMW'    => ['3 Series', '5 Series', 'X3'],
            'Lada'   => ['Vesta', 'Granta'],
        ];

        foreach ($brandData as $bName => $mNames) {
            $b = (new Brand())->setName($bName);
            $om->persist($b);
            $brands[] = $b;

            foreach ($mNames as $mn) {
                $m = (new Model())->setName($mn)->setBrand($b);
                $om->persist($m);
                $modelsByBrand[$bName][] = $m;
            }
        }

        foreach ($brands as $b) {
            $bName = $b->getName();
            foreach ($modelsByBrand[$bName] as $m) {
                for ($i = 0; $i < 2; $i++) {
                    $car = (new Car())
                        ->setBrand($b)
                        ->setModel($m)
                        ->setPhoto('https://fotland.ru/d/102240/d/Paint_004443.jpg')
                        ->setPrice($faker->numberBetween(600_000, 4_200_000));
                    $om->persist($car);
                }
            }
        }

        $p1 = (new CreditProgram())
            ->setTitle('Alfa Energy')
            ->setInterestRate(12.3)
            ->setMinInitialPayment('200000.00')
            ->setMaxMonthlyPayment(10000)
            ->setMaxLoanTermMonths(59);
        $om->persist($p1);

        $p2 = (new CreditProgram())
            ->setTitle('Standard 14.5')
            ->setInterestRate(14.5);
        $om->persist($p2);

        $p3 = (new CreditProgram())
            ->setTitle('Premium 10.9')
            ->setInterestRate(10.9)
            ->setMinInitialPayment('1000000.00')
            ->setMaxLoanTermMonths(36);
        $om->persist($p3);

        $om->flush();
    }
}
