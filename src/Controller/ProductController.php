<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use App\Repository\QuantityTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/product')]

class ProductController extends AbstractController
{
    #[Route(name: 'api_product_index', methods: ['GET'])]
    public function getAll(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $productList = $productRepository->findAll();

        $productJson = $serializer->serialize($productList, 'json', ['groups' => ["product", "productType", "quantityType"]]);

        return new JsonResponse($productJson, Response::HTTP_OK, [], true);
    }

    #[Route(path: '/{id}', name: 'api_product_show', methods: ["GET"])]
    public function get(Product $product, SerializerInterface $serializer): JsonResponse
    {

        $productJson = $serializer->serialize($product, 'json', ['groups' => ["product", "productType", "quantityType"]]);


        return new JsonResponse($productJson, Response::HTTP_OK, [], true);
    }

    #[Route(name: 'api_product_new', methods: ['POST'])]
    public function create(Request $request, ProductTypeRepository $productTypeRepository, QuantityTypeRepository $quantityTypeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $type = $productTypeRepository->find($data['type']);
        $quantityType = $quantityTypeRepository->find($data['quantityType']);

        $product = $serializer->deserialize($request->getContent(), Product::class, 'json', []);
        $product
            ->setType($type)->setStatus("on")
            ->setQuantityType($quantityType)->setStatus("on");

        $entityManager->persist($product);
        $entityManager->flush();

        $productJson = $serializer->serialize($product, 'json', ['groups' => ["product", "productType", "quantityType"]]);
        return new JsonResponse($productJson, Response::HTTP_CREATED, [], true);
    }
}
