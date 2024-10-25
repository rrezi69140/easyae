<?php

namespace App\Controller;

use App\Entity\ProductType;
use App\Repository\ProductTypeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/productType')]

class ProductTypeController extends AbstractController
{
    #[Route(name: 'app_product_type_index', methods: ["GET"])]
    public function getAll(ProductTypeRepository $productTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $productTypeList = $productTypeRepository->findAll();

        $productTypeJson = $serializer->serialize($productTypeList, 'json', ['groups' => 'productType']);

        return new JsonResponse($productTypeJson, Response::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_product_type', methods: ["GET"] )]
    public function get(ProductType $productType, SerializerInterface $serializer):JsonResponse
    {
        $productTypeJson = $serializer->serialize($productType, 'json', ['groups' => "productType"]);

        return new JsonResponse($productTypeJson, Response::HTTP_OK, [], true);
    }
}
