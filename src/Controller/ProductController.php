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
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;
    private ProductRepository $pr;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this -> em = $em;
        $this -> validator = $validator;
        $this -> serializer = $serializer;
        $this -> pr = $this -> em -> getRepository(Product::class);
    }

    #[Route('/products', name: 'app_products_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $body = $request -> getContent();
        $product = $this -> serializer -> deserialize($body, Product::class, 'json');
        $messages = array();
        $errors = $this -> validator->validate($body);
        $this->handleErrors($request,$errors, $messages);

        $this -> em -> persist($product);
        $this -> em -> flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/products/{id}', name: 'app_products_update', methods: ['PATCH'])]
    public function update(int $id ,Request $request)
    {
        $product = $this -> pr -> find($id);
        $messages = array();

        if($product)
        {
            $body = $request -> getContent();
            $updatedProduct = $this -> serializer -> deserialize($body, Product::class, 'json');
            $errors = $this -> validator->validate($body);
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
    public function destroy(int $id)
    {
        $product = $this -> pr -> find($id);

        if($product)
        {
            $this -> em -> remove($product);
            $this -> em -> flush();
        }

        return $this->redirectToRoute('app_admin');
    }
}
