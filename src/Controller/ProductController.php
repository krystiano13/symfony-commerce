<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductImageHandler;
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

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->pr = $this->em->getRepository(Product::class);
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
    public function create(Request $request, ProductImageHandler $imageHandler): Response
    {
        $body = [
            "name" => $request->request->get('name'),
            "amount" => $request->request->get('amount'),
            "price" => $request->request->get('price'),
        ];

        $messages = [];
        $errors = $this->validator->validate($body);
        $this->handleErrors($request, $errors, $messages);

        $product = new Product();
        $product->setName($request->request->get('name'));
        $product->setAmount($request->request->get('amount'));
        $product->setPrice($request->request->get('price'));

        $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/images/';

        $imageHandler -> handle($request, $uploadsDirectory, $product);

        $this->em->persist($product);
        $this->em->flush();

        return $this->json([
            "product" => $body,
        ], Response::HTTP_CREATED);
    }

    #[Route('/products/{id}', name: 'app_products_update', methods: ['POST'])]
    public function update(Request $request, int $id, ProductImageHandler $imageHandler):Response
    {
        $body = [
            "name" => $request->request->get('name'),
            "amount" => $request->request->get('amount'),
            "price" => $request->request->get('price'),
        ];

        $product = $this->pr->find($id);
        $messages = [];

        if ($product) {
            $errors = $this->validator->validate($body);
            $this->handleErrors($request, $errors, $messages);

            $product->setName($request->request->get('name'));
            $product->setAmount($request->request->get('amount'));
            $product->setPrice($request->request->get('price'));

            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/images/';
            $imageHandler -> handle($request, $uploadsDirectory, $product);
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
