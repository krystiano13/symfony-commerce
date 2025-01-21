<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class HomeController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(SerializerInterface $serializer): Response
    {
        $products = $this->productRepository->findAll();
        $productsSerialized = $serializer->serialize($products, 'json');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $productsSerialized,
        ]);
    }
}
