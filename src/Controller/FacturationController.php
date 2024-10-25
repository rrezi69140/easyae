<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Facturation;
use App\Repository\ContratRepository;
use App\Repository\FacturationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/facturation')]
class FacturationController extends AbstractController
{
    #[Route(name: 'api_facturation_index', methods: ["GET"])]
    public function getAll(FacturationRepository $contratRepository, SerializerInterface $serializer): JsonResponse
    {
        $facturationList = $contratRepository->findAll();

        $facturationJson = $serializer->serialize($facturationList, 'json', ['groups' => "facturation"]);

        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }


    #[Route(path: '/{id}', name: 'api_facturation_show', methods: ["GET"])]
    public function get(Facturation $facturation, SerializerInterface $serializer): JsonResponse
    {
        $facturationJson = $serializer->serialize($facturation, 'json', ['groups' => "facturation"]);

        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_facturation_new', methods: ["POST"])]
    public function create(Request $request, ContratRepository $contratRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $contrat = $contratRepository->find($data["contrat"]);
        $facturation = $serializer->deserialize($request->getContent(), Facturation::class, 'json', []);
        $facturation->setcontrat($contrat)
            ->setStatus("on")
        ;
        $entityManager->persist($facturation);
        $entityManager->flush();
        $contratJson = $serializer->serialize($facturation, 'json', ['groups' => "facturation"]);
        return new JsonResponse($contratJson, JsonResponse::HTTP_OK, [], true);
    }
}
