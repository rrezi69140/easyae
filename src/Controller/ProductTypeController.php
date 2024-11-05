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
        $productType = $serializer->deserialize($request->getContent(), ProductType::class, 'json', []);
        $productType->setClient($productType)
            ->setStatus("on")
        ;
        $entityManager->persist($productType);
        $entityManager->flush();
        $productTypeJson = $serializer->serialize($productType, 'json', ['groups' => "productType"]);
        return new JsonResponse($productTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: "/{id}", name: 'api_product_type_edit', methods: ["PATCH"])]
    public function update(ProductType $productType, UrlGeneratorInterface $urlGenerator, Request $request, ProductTypeRepository $productTypeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['productType'])) {

            $productType = $productTypeRepository->find($data["productType"]);
        }


        $updatedProductType = $serializer->deserialize($request->getContent(), ProductType::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $productType]);
        $updatedProductType
            ->setProductType($client ?? $updatedProductType->getProductType())
            ->setStatus("on")
        ;

        $entityManager->persist($updatedProductType);
        $entityManager->flush();
        $location = $urlGenerator->generate("api_product_type", ['id' => $updatedProductType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }
    #[Route(path: "/{id}", name: 'api_product_type_delete', methods: ["DELETE"])]
    public function delete(ProductType $productType, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($productType);


        } else {
            $productType
                ->setStatus("off")
            ;

            $entityManager->persist($productType);
        }



        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }


}
