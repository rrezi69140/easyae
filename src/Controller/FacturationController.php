<?php

namespace App\Controller;

use App\Entity\Facturation;
use App\Repository\FacturationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/facturation')]
class FacturationController extends AbstractController
{
    #[Route(name: 'api_facturation_index', methods: ["GET"])]
    public function getAll(FacturationRepository $accountRepository, SerializerInterface $serializer): JsonResponse
    {
        $facturationList = $accountRepository->findAll();

        $facturationJson = $serializer->serialize($facturationList, 'json', ['groups' => "facturation"]);

        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }


    #[Route(path: '/{id}', name: 'api_facturation_show', methods: ["GET"])]
    public function get(Facturation $facturation, SerializerInterface $serializer): JsonResponse
    {
        $facturationJson = $serializer->serialize($facturation, 'json', ['groups' => "facturation"]);

        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }
}
