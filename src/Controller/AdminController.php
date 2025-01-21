<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class AdminController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(SerializerInterface $serializer): Response
    {
        $allProduts = $this->productRepository->findAll();
        $productsSerialized = $serializer -> serialize($allProduts, 'json');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'products' => $productsSerialized
        ]);
    }
}
