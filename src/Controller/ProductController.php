<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    /**
     * Permet d'afficher tous les produits
     * @param ProductRepository $repo
     * @return Response
     */
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $repo): Response
    {
        $products = $repo->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Permet d'ajouter un produit à la bdd
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/product/new', name: 'app_product_new')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/new.html.twig', [
            "myForm" => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher un produit (via son id)
     * @param Product $product
     * @param int $id
     * @return Response
     */
    #[Route('/product/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product, int $id): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
