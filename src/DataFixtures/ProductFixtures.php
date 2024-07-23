<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Domain\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Kamil Gąsior <kamilgasior07@gmail.com>
 */
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $product = new Product();
            $product->setName('Sample Product' . $i);
            $product->setPrice(10000);

            $manager->persist($product);
        }
        $manager->flush();
    }
}
