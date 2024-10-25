<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\FacturationModel;

#[Route('/api/facturation-model')]

class FacturationModelController extends AbstractController
{
    #[Route(name: 'api_facturation_model_index', methods: ["GET"])]
    public function getAll(FacturationModelRepository $facturationModelRepository, SerializerInterface $serializer): JsonResponse
    {
        $facturationModelList = $facturationModelRepository->findAll();

        $facturationModelJson = $serializer->serialize($facturationModelList, 'json', ['groups' => "facturationModel"]);


        return new JsonResponse($facturationModelJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_facturation_model_show', methods: ["GET"])]
    public function get(FacturationModel $facturationModel, SerializerInterface $serializer): JsonResponse
    {
        $facturationModelJson = $serializer->serialize($facturationModel, 'json', ['groups' => "facturationModel"]);

        return new JsonResponse($facturationModelJson, JsonResponse::HTTP_OK, [], true);
    }
}