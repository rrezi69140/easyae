<?php

namespace App\Controller;

use App\Entity\Fonction;
use App\Repository\FonctionRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/fonction')]

class FonctionController extends AbstractController
{
    #[Route(name: 'app_fonction', methods: ["GET"])]
    public function getAll(FonctionRepository $fonctionRepository, SerializerInterface $serializer): JsonResponse
    {
        $fonctionList = $fonctionRepository->findAll();

        $fonctionJson = $serializer->serialize($fonctionList, 'json', ['groups' => "fonction"]);
        return new JsonResponse($fonctionJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_fonction_show', methods: ["GET"])]
    public function get(Fonction $fonction, SerializerInterface $serializer): JsonResponse
    {
        $fonctionJson = $serializer->serialize($fonction, 'json', ['groups' => "fonction"]);
        return new JsonResponse($fonctionJson, JsonResponse::HTTP_OK, [], true);
    }
}
