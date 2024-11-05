<?php

namespace App\Controller;

use App\Entity\ContratType;
use App\Repository\ContratTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/contrat-type')]

class ContratTypeController extends AbstractController
{
    #[Route(name: 'api_contrat_type_index', methods: ["GET"])]
    public function getAll(ContratTypeRepository $contratTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $contratTypeList = $contratTypeRepository->findAll();

        $contratTypeJson = $serializer->serialize($contratTypeList, 'json', ['groups' => "contratType"]);

        return new JsonResponse($contratTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_contrat_type_show', methods: ["GET"])]
    public function get(ContratType $contratType, SerializerInterface $serializer): JsonResponse
    {
        $contratTypeJson = $serializer->serialize($contratType, 'json', ['groups' => "contratType"]);

        return new JsonResponse($contratTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_contrat_type_new', methods: ["POST"])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $contratType = $serializer->deserialize($request->getContent(), ContratType::class, 'json', []);
        $contratType->setStatus("on");
        $entityManager->persist($contratType);
        $entityManager->flush();
        $contratTypeJson = $serializer->serialize($contratType, 'json', ['groups' => "contratType"]);
        return new JsonResponse($contratTypeJson, JsonResponse::HTTP_OK, [], true);
    }
}
