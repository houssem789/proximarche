<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ProductManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save($data)
    {
        $category = $this->em->getRepository(Category::class)->findOneById($data['categorie']);

        $product = new Product();

        $product->setNameProduct($data['product_name']);
        $product->setExpirationDate(new \DateTime($data['expiration_date']));
        $product->setPurshaseDate(new \DateTime($data['purshase_date']));
        $product->setDetailsProduct(strcmp($data['product_details'], '') == 0 ? 'no-details' : $data['product_details']);
        $product->setQuantity($data['quantity']);
        $product->setCategory($category);

        $this->commit($product);
    }


    public function commit($product)
    {
        $this->em->persist($product);
        $this->em->flush();
    }
}
