<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\QuantityType;
use App\Repository\ClientRepository;
use App\Repository\QuantityTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/quantity-type')]

class QuantityTypeController extends AbstractController
{
    #[Route(name: 'api_quantity_type_index', methods: ["GET"])]
    public function getAll(QuantityTypeRepository $quantityTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $quantityTypeList = $quantityTypeRepository->findAll();

        $quantityTypeJson = $serializer->serialize($quantityTypeList, 'json', ['groups' => "quantityType"]);

        return new JsonResponse($quantityTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_quantity_type_show', methods: ["GET"])]
    public function get(QuantityType $quantityType, SerializerInterface $serializer): JsonResponse
    {
        $quantityTypeJson = $serializer->serialize($quantityType, 'json', ['groups' => "quantityType"]);

        return new JsonResponse($quantityTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_quantity_type_new', methods: ["POST"])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $quantityType = $serializer->deserialize($request->getContent(), QuantityType::class, 'json', []);
        $quantityType->setStatus("on");
        $entityManager->persist($quantityType);
        $entityManager->flush();
        $accountJson = $serializer->serialize($quantityType, 'json', ['groups' => "quantityType"]);
        return new JsonResponse($accountJson, JsonResponse::HTTP_OK, [], true);
    }
}
