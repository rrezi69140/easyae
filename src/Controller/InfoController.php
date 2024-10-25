<?php

namespace App\Controller;

use App\Entity\Info;
use App\Repository\ClientRepository;
use App\Repository\InfoRepository;
use App\Repository\InfoTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;


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

        $infoJson = $serializer->serialize($info, 'json', ['groups' => "info"]);


        return new JsonResponse($infoJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_info_new', methods: ["POST"])]

    public function create(Request $request, SerializerInterface $serializer, InfoTypeRepository $infoTypeRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $infoType = $infoTypeRepository->find($data["type"]);
        $info = $serializer->deserialize($request->getContent(), Info::class, 'json', []);
        $info->setType($infoType)
            ->setStatus("on")
        ;
        $entityManager->persist($info);
        $entityManager->flush();
        $infoJson = $serializer->serialize($info, 'json', ['groups' => "info"]);
        return new JsonResponse($infoJson, JsonResponse::HTTP_OK, [], true);
    }
}
