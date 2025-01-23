<?php

namespace App\Service\Products;

use App\Entity\Product;
use App\Service\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductImageHandler
{
    public function handle(Request $request, string $uploadsDirectory , Product &$product) {
        $file = $request->files->get('image');

        if($file)
        {
            try {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uploadsDirectory, $filename);
                $product->setImageSrc("/images/" . $filename);
            } catch (FileException $e) {
                return $this->json(['error' => 'Failed to upload file'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}