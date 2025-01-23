<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Repository\OrderRepository;
use App\Entity\Order;
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

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/order', name: 'app_order')]
    public function index(OrderRepository $orderRepository): Response
    {
        $data = $orderRepository->findAll();

        return $this->json([
            "orders" => $data,
        ], Response::HTTP_OK);
    }

    #[Route('/order', name: 'app_order_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $messages = array();

        $body = $request->getContent();
        $order = $this->serializer->deserialize($body,Order::class, 'json');
        $errors = $this -> validator->validate($order);

        $this -> handleErrors($request, $errors, $messages);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->json([
            "order" => $order,
        ], Response::HTTP_CREATED);
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
