<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Self_;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        ['Corps', 'body'],
       [ 'Cheveux', 'body'],
        ['Vernis', 'makeup'],
        ['Maquillage', 'makeup'],
        ['Visage','body'],
    ];
    public function load(ObjectManager $manager)
    {
        $key = 0;
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName[0]);
            $category->setIdentifier(($categoryName[1]));
            $manager->persist($category);
            $this->addReference('category_' . $key, $category);
            $key++;
        }
        $manager->flush();
    }


}
