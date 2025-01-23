<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cart;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CartController extends AbstractController
{
    use ValidationErrorTrait;

    private EntityManagerInterface $entityManager;
    private CartRepository $cartRepository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, CartRepository $cartRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->cartRepository = $cartRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart', name: 'app_cart_create', methods: ['POST'])]
    public function create(Request $request):Response
    {
        $body = $request->getContent();
        $cart = $this->serializer->deserialize($body, Cart::class, 'json');
        $errors = $this -> validator->validate($cart);
        $messages = array();
        $this->handleErrors($request, $errors, $messages);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $this->json([
            "cart" => $cart
        ], Response::HTTP_CREATED);
    }

    #[Route('/cart/{id}', name: 'app_cart_update', methods: ['PATCH'])]
    public function update(Request $request, int $id):Response
    {
        $cart = $this->cartRepository->find($id);
        $messages = array();
        $errors = array();

        if($cart)
        {
            $body = $request->getContent();
            $updatedCart = $this->serializer->deserialize($body, Cart::class, 'json');
            $errors = $this -> validator->validate($updatedCart);
            $this->handleErrors($request, $errors, $messages);

            $cart->setAmount($updatedCart->getAmount());

            $this->entityManager->flush();
            return $this->json([
                "cart" => $updatedCart
            ], Response::HTTP_OK);
        }

        return $this->json(["errors" => ["Cart Not Found"]], Response::HTTP_NOT_FOUND);
    }

    #[Route('/cart/{id}', name: 'app_cart_destroy', methods: ['DELETE'])]
    public function destroy(int $id): Response
    {
        $cart = $this->cartRepository->find($id);

        if($cart)
        {
            $this->entityManager->remove($cart);
            $this->entityManager->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}