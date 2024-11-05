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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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

    #[Route(path: "/{id}", name: 'api_quantity_type_edit', methods: ["PATCH"])]
    public function update(QuantityType $quantityType, UrlGeneratorInterface $urlGenerator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $updatedQuantityType = $serializer->deserialize($request->getContent(), QuantityType::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $quantityType]);
        $updatedQuantityType->setStatus("on");

        $entityManager->persist($updatedQuantityType);
        $entityManager->flush();
        $location = $urlGenerator->generate("api_quantity_type_show", ['id' => $updatedQuantityType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }

    #[Route(path: "/{id}", name: 'api_quantity_type_delete', methods: ["DELETE"])]
    public function delete(QuantityType $quantityType, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($quantityType);


        } else {
            $quantityType
                ->setStatus("off")
            ;
            $entityManager->persist($quantityType);
        }
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
