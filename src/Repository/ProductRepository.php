<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findFavoritesOrHated($user, $choice)
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :u')->setParameter('u', $user)
            ->andWhere('p.' . $choice . '= true')
            ->getQuery()
            ->getResult();
    }

    public function findLike($search, $user)
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :n')->setParameter('n', '%' . $search . '%'  )
            ->andWhere('p.user= :u')->setParameter('u', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param SearchData $searchData
     * @return Product[]|array
     */
    public function findSearch(SearchData $searchData): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c');

        if (!empty($searchData->q)) {
            $query = $query->andWhere('p.name LIKE :q')
                    ->setParameter('q', '%' . $searchData->q . '%');
        }
        if (!empty($searchData->categories)) {
            $query = $query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $searchData->categories);
        }

        return $query->getQuery()->getResult();
    }

    public function searchByKeyWords(?array  $keywords)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        foreach ($keywords as $i=>$keyword) {
            $queryBuilder->orWhere('p.name LIKE :name' . $i)
                ->setParameter('name' . $i, '%' . $keyword . '%');
        }
        return $queryBuilder->getQuery()->getResult();

    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
