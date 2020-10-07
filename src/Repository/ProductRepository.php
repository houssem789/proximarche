<?php

namespace App\Repository;

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


    public function getProductsPurshasedByPeriod($startDate, $endDate, $page = 1)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.purshaseDate BETWEEN :initialDate AND :finalDate')
            ->setParameter('initialDate', $startDate->format('Y-m-d'))
            ->setParameter('finalDate', $endDate->format('Y-m-d'))
            ->setMaxResults(5)
            ->getQuery();

        $pageSize = '10';

        //Load doctrine Paginator
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        //You can get total items
        $totalItems = count($paginator);

        //Get total pages
        $pagesCount = ceil($totalItems / $pageSize);

        //Data 
        $data = [];
        
        //Now get one page's items:
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page - 1)) // set the offset
            ->setMaxResults($pageSize); // set the limit

        foreach ($paginator as $pageItem) {
            $data[] = $pageItem;
        }
        // return stuff..
        return ["data" => $data, "totalItems" => $totalItems, "pages" => $pagesCount];
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
