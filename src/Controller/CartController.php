<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
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

    #[Route('/cart', name: 'app_cart', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart/get', name: 'app_cart_get', methods: ['GET'])]
    public function getCart(): Response
    {
        $conn = $this->entityManager->getConnection();
        $statement = " SELECT cart.*, product.name, product.price*cart.amount as price from cart inner join product on cart.product_id = product.id WHERE cart.user_id = :user_id";

        $stmt = $conn -> executeQuery($statement, [
            'user_id' => $this->getUser()->getId(),
        ]);

        return $this -> json([
            "cart" => $stmt->fetchAllAssociative()
        ], Response::HTTP_OK);
    }

    #[Route('/cart', name: 'app_cart_create', methods: ['POST'])]
    public function create(Request $request, ProductRepository $productRepository):Response
    {
        $body = $request->getContent();
        $cart = $this->serializer->deserialize($body, Cart::class, 'json');
        $cart -> setAmount(1);

        $product = $productRepository -> find($cart->getProductId());
        $cartCheck = $this->cartRepository->findBy(["user_id" => $this->getUser()->getId(), "product_id" => $cart->getProductId()]);

        $errors = $this -> validator->validate($cart);
        $messages = array();
        $this->handleErrors($request, $errors, $messages);

        if($product->getAmount() > 0)
        {
            if($cartCheck)
            {
                $cartCheck[0] -> setAmount($cartCheck[0] -> getAmount() + 1);
            }
            else
            {
                $this->entityManager->persist($cart);
            }

            $product -> setAmount($product -> getAmount() - 1);
        }

        $this->entityManager->flush();

        return $this->json([
            "cart" => $body,
            "messages" => $messages
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
    public function destroy(int $id, ProductRepository $productRepository): Response
    {
        $cart = $this->cartRepository->find($id);

        if($cart)
        {
            $product = $productRepository -> find($cart->getProductId());

            if($product)
            {
                $product -> setAmount($product -> getAmount() + 1);
            }

            $this->entityManager->remove($cart);
            $this->entityManager->flush();
        }

        return $this->json(["message" => "deleted"], Response::HTTP_OK);
    }
}