<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Services\Helper;
use App\Services\ProductManager;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/dashbord", name="dashbord")
     */
    public function dashbord(Request $request, Helper $helper)
    {
        $repo        = $this->getDoctrine()->getRepository(Product::class);
        $startDate   = date("Y-m-d");
        $endDate     = (new \DateTime($startDate))->modify('+2 day')->format('Y-m-d');

        $liquidProduct = $repo->getProductsByExpirationDate($startDate, $endDate, 'Liquide');
        $creamyProduct = $repo->getProductsByExpirationDate($startDate, $endDate, 'crémerie');

        return $this->render('product/dashbord.html.twig', [
            'liquidProducts' => $liquidProduct,
            'creamyProducts' => $creamyProduct
        ]);
    }

    /**
     * @Route("/products", name="products")
     */
    public function index(Request $request, Helper $helper)
    {
        $em        = $this->getDoctrine();
        $startDate = new DateTime('first day of this month');
        $endDate   = new DateTime('last day of this month');


        $products = $em->getRepository(Product::class)->getProductsPurshasedByPeriod($startDate, $endDate);

        return $this->render('product/index.html.twig', [
            'products' => $products['data']
        ]);
    }


    /**
     * @Route("/products/{date}/{page}", name="products_by_month", defaults={"page"=1})
     */
    public function productsPurshaseByMonth(Request $request, Helper $helper)
    {
        $em   = $this->getDoctrine();
        $data = $request->attributes->all();
        $page = $data['page'];

        if (isset($data['date']) && $helper->valid_date($data['date'], 'd-m-Y')) {
            // First day of the month.
            $date =  new DateTime($data['date']);
            $dateClone =  new DateTime($data['date']);

            $startDate = $date->modify('first day of this month');
            $endDate   = $dateClone->modify('last day of this month');

            $products = $em->getRepository(Product::class)
                ->getProductsPurshasedByPeriod($startDate, $endDate, $page);

            return $this->render('product/index.html.twig', [
                'products'  => $products['data'],
                'lastPage'  => $products['pages'],
                'startDate' => $startDate,
                'endDate'   => $endDate
            ]);
        }
    }

    /**
     * @Route("/add-product", name="add_product")
     */
    public function addProduct(Request $request, ProductManager $productManager): Response
    {
        $em = $this->getDoctrine();
        $categories = $em->getRepository(Category::class)->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $request->request->all();
            $productManager->save($data);
        }

        $todayDate = date("Y-m-d");

        return $this->render("product/product-form.html.twig", [
            "categories" => $categories,
            "todayDate" => $todayDate
        ]);
    }
}
