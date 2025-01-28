<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Entity\Order;
use App\Enums\OrderStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class OrderController extends AbstractController
{
    use ValidationErrorTrait;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    private EntityManagerInterface $entityManager;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    #[Route('/order', name: 'app_order', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        $data = $orderRepository->findAll();

        return $this->json([
            "orders" => $data,
        ], Response::HTTP_OK);
    }

    #[Route('/order', name: 'app_order_create', methods: ['POST'])]
    public function create(Request $request, CartRepository $cartRepository): Response
    {
        $messages = array();

        $body = $request->getContent();
        $order = $this->serializer->deserialize($body,Order::class, 'json');
        $errors = $this -> validator->validate($order);

        $this -> handleErrors($request, $errors, $messages);

        $this->entityManager->persist($order);

        $cartItems = $cartRepository->findBy(['user_id' => $this->getUser()->getId()]);

        foreach ($cartItems as $cartItem)
        {
            $this->entityManager->remove($cartItem);
        }

        $this->entityManager->flush();

        return $this->json([
            "order" => $order,
        ], Response::HTTP_CREATED);
    }

    #[Route('/order/{id}', name: 'app_order_update', methods: ['PATCH'])]
    public function update(int $id, OrderRepository $repository): Response
    {
        $order = $repository->find($id);

        if($order)
        {
            $order->setStatus(OrderStatusEnum::Delivered);
            $this->entityManager->flush();

            return $this->json([
                "order" => "Delivered",
            ], Response::HTTP_OK);
        }

        return $this->json(["errors" => ["Cart Not Found"]], Response::HTTP_NOT_FOUND);
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
