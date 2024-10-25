<?php

namespace App\Controller;

use App\Entity\QuantityType;
use App\Repository\QuantityTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/quantity-type')]

class QuantityTypeController extends AbstractController
{
    #[Route(name: 'api_quantityType_index', methods: ["GET"])]
    public function getAll(QuantityTypeRepository $quantityTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $quantityTypeList = $quantityTypeRepository->findAll();

        $quantityTypeJson = $serializer->serialize($quantityTypeList, 'json', ['groups' => "quantityType"]);

        return new JsonResponse($quantityTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_quantityType_show', methods: ["GET"])]
    public function get(QuantityType $quantityType, SerializerInterface $serializer): JsonResponse
    {
        $quantityTypeJson = $serializer->serialize($quantityType, 'json', ['groups' => "quantityType"]);

        return new JsonResponse($quantityTypeJson, JsonResponse::HTTP_OK, [], true);
    }
}
