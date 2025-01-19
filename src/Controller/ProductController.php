<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Repository\ProductRepository;
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
    public function update($id ,Request $request, ValidatorInterface $validator, ProductRepository $pr, SerializerInterface $sr)
    {
        $product = $pr -> find($id);
        $messages = array();
        $errors = array();

        if($product)
        {
            $body = $request -> getContent();
            $updatedProduct = $sr -> deserialize($body, Product::class, 'json');
            $errors = $validator->validate($body);
            $this -> handleErrors($request,$errors, $messages);

            $product -> setName($updatedProduct -> getName());
            $product -> setPrice($updatedProduct -> getPrice());
            $product -> setAmount($updatedProduct -> getAmount());
            $product -> setImageSrc($updatedProduct -> getImageSrc());

            $this -> em -> flush();

        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/products/{id}', name: 'app_products_delete', methods: ['DELETE'])]
    public function destroy()
    {
        return $this->redirectToRoute('app_admin');
    }
}
