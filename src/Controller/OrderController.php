<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        $data = $orderRepository->findAll();

        return $this->json([
            "orders" => $data,
        ], Response::HTTP_OK);
    }

    #[Route('/order', name: 'app_order_create', methods: ['POST'])]
    public function create()
    {

    }

    #[Route('/order/{id}', name: 'app_order_update', methods: ['PATCH'])]
    public function update()
    {

    }

    #[Route('/order/{id}', name: 'app_order_destroy', methods: ['DELETE'])]
    public function destroy(int $id, OrderRepository $orderRepository, EntityManagerInterface $em): Response
    {
        $order = $orderRepository->find($id);

        if($order)
        {
            $em->remove($order);
            $em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
