<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/product')]

class ProductController extends AbstractController
{
    #[Route(name: 'app_product_index', methods: ['GET'])]
    public function getAll(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $productList = $productRepository->findAll();

        $productJson = $serializer->serialize($productList, 'json', ['groups' => 'product']);

        return new JsonResponse($productJson, Response::HTTP_OK, [], true);
    }

    #[Route(path: '/{id}', name: 'api_product_show', methods: ["GET"])]
    public function get(Product $product, SerializerInterface $serializer): JsonResponse
    {

        $accountJson = $serializer->serialize($product, 'json', ['groups' => "product"]);


        return new JsonResponse($accountJson, Response::HTTP_OK, [], true);
    }
}
