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
        $product = new Product();
        $product->setName('Sample Product');
        $product->setPrice(100);

        $manager->persist($product);
        $manager->flush();
    }
}
