<?php

namespace App\Controller;

use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/contrat')]

class ContratController extends AbstractController
{
    #[Route(name: 'api_contrat_index', methods: ["GET"])]
    public function getAll(ContratRepository $contratRepository, SerializerInterface $serializer): JsonResponse
    {
        $contratList = $contratRepository->findAll();

        $contratJson = $serializer->serialize($contratList, 'json', ['groups' => "contrat"]);


        return new JsonResponse($contratJson, JsonResponse::HTTP_OK, [], true);
    }
}
