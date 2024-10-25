<?php

namespace App\Controller;

use App\Entity\Info;
use App\Repository\InfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/info')]

class InfoController extends AbstractController
{
    #[Route(name: 'api_info_index', methods: ["GET"])]
    public function getAll(InfoRepository $infoRepository, SerializerInterface $serializer): JsonResponse
    {
        $infoList = $infoRepository->findAll();

        $infoJson = $serializer->serialize($infoList, 'json', ['groups' => ["info", "infoType"]]);


        return new JsonResponse($infoJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_info_show', methods: ["GET"])]
    public function get(Info $info, SerializerInterface $serializer): JsonResponse
    {
        // $infoList = $infoRepository->find($id);

        $infoJson = $serializer->serialize($info, 'json', ['groups' => ["info", "infoType"]]);


        return new JsonResponse($infoJson, JsonResponse::HTTP_OK, [], true);
    }
}
