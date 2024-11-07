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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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

    #[Route(path: "/{id}", name: 'api_client_edit', methods: ["PATCH"])]
    public function update(Client $client, UrlGeneratorInterface $urlGenerator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $updatedclient = $serializer->deserialize($request->getContent(), Client::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $client]);
        $updatedclient->setStatus("on");

        $entityManager->persist($updatedclient);
        $entityManager->flush();
        $location = $urlGenerator->generate("api_quantity_type_show", ['id' => $updatedclient->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }

    #[Route(path: "/{id}", name: 'api_client_delete', methods: ["DELETE"])]
    public function delete(client $client, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($client);


        } else {
            $client
                ->setStatus("off")
            ;
            $entityManager->persist($client);
        }
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
