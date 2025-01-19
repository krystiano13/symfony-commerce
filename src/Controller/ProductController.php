<?php

namespace App\Controller;

use App\Controller\Trait\ValidationErrorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProductController extends AbstractController
{
    use ValidationErrorTrait;

    #[Route('/products', name: 'app_products_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        $body = $request -> getContent();
        $errors = $validator->validate($body);

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
