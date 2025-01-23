<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Products\ProductDataHandler;
use App\Service\Products\ProductImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    private ProductImageHandler $imageHandler;
    private ProductDataHandler $dataHandler;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->pr = $this->em->getRepository(Product::class);
        $this->imageHandler = new ProductImageHandler();
        $this->dataHandler = new ProductDataHandler();
    }

    #[Route('/products', name: 'app_product_index', methods: ['GET', 'HEAD'])]
    public function index(Request $request): Response
    {
        $products = $this->pr->findAll();
        return $this->json([
            'products' => $products,
        ], Response::HTTP_OK);
    }

    #[Route('/products', name: 'app_products_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $body = $this -> dataHandler -> getData($request);
        $messages = [];
        $errors = $this->validator->validate($body);
        $this->handleErrors($request, $errors, $messages);

        $product = new Product();
        $this -> dataHandler -> setData($request, $product);

        $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/images/';
        $this -> imageHandler -> handle($request, $uploadsDirectory, $product);

        $this->em->persist($product);
        $this->em->flush();

        return $this->json([
            "product" => $body,
        ], Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'app_products_update', methods: ['POST'])]
    public function update(Request $request, int $id):Response
    {
        $body = $this -> dataHandler -> getData($request);
        $product = $this->pr->find($id);
        $messages = [];

        if ($product) {
            $errors = $this->validator->validate($body);
            $this->handleErrors($request, $errors, $messages);
            $this -> dataHandler -> setData($request, $product);
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/images/';
            $this -> imageHandler -> handle($request, $uploadsDirectory, $product);
            $this->em->flush();
        }

        return $this->json([
            "product" => $product
        ], Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'app_products_delete', methods: ['DELETE'])]
    public function destroy(int $id)
    {
        $product = $this->pr->find($id);

        if ($product) {
            $this->em->remove($product);
            $this->em->flush();
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
