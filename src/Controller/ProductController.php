<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProductController extends AbstractController
{
    use ValidationErrorTrait;

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/products', name: 'app_products_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator, SerializerInterface $sr): Response
    {
        $body = $request -> getContent();
        $product = $sr -> deserialize($body, Product::class, 'json');
        $messages = array();
        $errors = $validator->validate($body);
        $this->handleErrors($request,$errors, $messages);

        $this -> em -> persist($product);
        $this -> em -> flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/products/{id}', name: 'app_products_update', methods: ['PATCH'])]
    public function update()
    {
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/products/{id}', name: 'app_products_delete', methods: ['DELETE'])]
    public function destroy()
    {
        return $this->redirectToRoute('app_admin');
    }
}
