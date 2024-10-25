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

    #[Route(name: 'api_facturation_model_new', methods: ["POST"])]
    public function create(Request $request, ClientRepository $clientRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $facturationModel = $serializer->deserialize($request->getContent(), FacturationModel::class, 'json', []);
        $facturationModel->setClient($client)
            ->setStatus("on")
        ;
        $entityManager->persist($facturationModel);
        $entityManager->flush();
        $facturationModelJson = $serializer->serialize($facturationModel, 'json', ['groups' => "facturationModel"]);
        return new JsonResponse($facturationModelJson, JsonResponse::HTTP_OK, [], true);
    }
}