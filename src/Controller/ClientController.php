<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/client')]
class ClientController extends AbstractController
{
    #[Route(name: 'api_client_index', methods: ["GET"])]
    public function getAll(ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $clientList = $clientRepository->findAll();

        $clientJson = $serializer->serialize($clientList, 'json', ['groups' => ["client", "clientType"]]);

        return new JsonResponse($clientJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: '/{id}', name: 'api_client_show', methods: ["GET"])]
    public function get(Client $client = null, SerializerInterface $serializer): JsonResponse
    {
        if (!$client) {
            return new JsonResponse(['error' => 'Client not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $clientJson = $serializer->serialize($client, 'json', ['groups' => ["client"]]);

        return new JsonResponse($clientJson, JsonResponse::HTTP_OK, [], true);
    }


    #[Route(name: 'api_client_new', methods: ["POST"])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');

        if (!$client) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (is_null($client->getStatus())) {
            $client->setStatus("on");
        }

        $contact = $client->getContact();
        if ($contact) {
            $entityManager->persist($contact);
        }

        $entityManager->persist($client);
        $entityManager->flush();

        $clientJson = $serializer->serialize($client, 'json', ['groups' => "client"]);
        return new JsonResponse($clientJson, JsonResponse::HTTP_CREATED, [], true);
    }

}
