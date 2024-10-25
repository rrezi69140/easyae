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

    #[Route(name: 'api_product_type_new', methods: ["POST"])]
    public function create(Request $request, ProductTypeRepository $productTypeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $productType = $serializer->deserialize($request->getContent(), ProductType::class, 'json', []);
        $productType->setClient($productType)
            ->setStatus("on")
        ;
        $entityManager->persist($productType);
        $entityManager->flush();
        $productTypeJson = $serializer->serialize($productType, 'json', ['groups' => "productType"]);
        return new JsonResponse($productTypeJson, JsonResponse::HTTP_OK, [], true);
    }


}
