<?php

namespace App\Service\Products;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class ProductDataHandler
{
    public function getData(Request $request):array {
        return [
            "name" => $request->request->get('name'),
            "amount" => $request->request->get('amount'),
            "price" => $request->request->get('price'),
        ];
    }

    public function setData(Request $request, Product &$product):void {
        $product->setName($request->request->get('name'));
        $product->setAmount($request->request->get('amount'));
        $product->setPrice($request->request->get('price'));
    }
}