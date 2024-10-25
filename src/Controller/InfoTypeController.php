<?php

namespace App\Controller;

use App\Entity\InfoType;
use App\Repository\InfoTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
#[Route('/api/infoType')]
class InfoTypeController extends AbstractController
{
    #[Route(name: 'api_InfoType_index', methods: ["GET"])]
    public function getAll(InfoTypeRepository $infoTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $infoTypeList = $infoTypeRepository->findAll();

        $infoTypeJson = $serializer->serialize($infoTypeList, 'json', ['groups' => "infoType"]);


        return new JsonResponse($infoTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_infoType_show', methods: ["GET"])]
    public function get(InfoType $infoType, SerializerInterface $serializer): JsonResponse
    {
        // $infoTypetList = $infoTypeRepository->find($id);

        $infoTypeJson = $serializer->serialize($infoType, 'json', ['groups' => "infoType"]);


        return new JsonResponse($infoTypeJson, JsonResponse::HTTP_OK, [], true);
    }
}
