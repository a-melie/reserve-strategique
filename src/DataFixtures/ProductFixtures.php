<?php


namespace App\DataFixtures;


use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    const PRODUCTS = [
        'A to Z'=>[
            'brand'=>'OPI',
            'size'=>'10',
            'color'=>'rouge',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_2',
        ],
        'Burgundy'=>[
            'brand'=>'Essie',
            'size'=>'10',
            'color'=>'rouge',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_2',
        ],
        'Pimp my shoes'=>[
            'brand'=>'Essie',
            'size'=>'10',
            'color'=>'rouge',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_2',
        ],
        'paradize island'=>[
            'brand'=>'Essie',
            'size'=>'10',
            'color'=>'vert',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_2',
        ],
        'satin sister'=>[
            'brand'=>'Essie',
            'size'=>'10',
            'color'=>'bleu',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_2',
        ],
        'rouge à lèvre'=>[
            'brand'=>'Maybelline',
            'size'=>'7',
            'color'=>'rouge',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_3',
        ],
        '194'=>[
            'brand'=>'Maybelline',
            'size'=>'7',
            'color'=>'rouge',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_3',
        ],
        'Lover'=>[
            'brand'=>'Maybelline',
            'size'=>'7',
            'color'=>'rouge',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_3',
        ],
        'Pathfinder'=>[
            'brand'=>'Maybelline',
            'size'=>'7',
            'color'=>'rose',
            'isFavorite'=>false,
            'isHated'=>true,
            'categorie'=>'category_3',
        ],
        'gel douche vanille'=>[
            'brand'=>'Petit Marseillais',
            'size'=>'200',
            'color'=>'',
            'isFavorite'=>true,
            'isHated'=>false,
            'categorie'=>'category_0',
        ],
        'haribo'=>[
            'brand'=>'dop',
            'size'=>'200',
            'color'=>'',
            'isFavorite'=>false,
            'isHated'=>true,
            'categorie'=>'category_0',
        ],
        'shampooing herbal'=>[
            'brand'=>'Herbal Essence',
            'size'=>'200',
            'color'=>'',
            'isFavorite'=>false,
            'isHated'=>true,
            'categorie'=>'category_1',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $key = 0;
        foreach (self::PRODUCTS as $name=>$data) {
            $product = new Product();
            $product->setName($name);
            $product->setBrand($data['brand']);
            $product->setCategory($this->getReference($data['categorie']));
            $product->setColor($data['color']);
            $product->setIsFavorite($data['isFavorite']);
            $product->setIsHated($data['isHated']);
            $product->setSize($data['size']);
            $product->setCreatedAt(new Datetime());
            $product->setUser($this->getReference('user_1'));
            $manager->persist($product);
            $this->addReference('product_' . $key, $product);
            $key++;
        }
        $manager->flush();
    }
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [UserFixture::class];
    }
}
