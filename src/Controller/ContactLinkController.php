<?php

namespace App\Controller;

use App\Entity\ContactLink;
use App\Repository\ContactLinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/contact_link')]

class ContactLinkController extends AbstractController
{
    #[Route(name: 'api_contact_link_index', methods: ["GET"])]
    public function getAll(ContactLinkRepository $contactLinkRepository, SerializerInterface $serializer): JsonResponse
    {
        $contactLinkList = $contactLinkRepository->findAll();

        $contactLinkJson = $serializer->serialize($contactLinkList, 'json', ['groups' => "contactLink"]);


        return new JsonResponse($contactLinkJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_contact_link_show', methods: ["GET"])]
    public function get(ContactLink $contactLink, SerializerInterface $serializer): JsonResponse
    {
        // $contactLinkList = $contactLinkRepository->find($id);

        $contactLinkJson = $serializer->serialize($contactLink, 'json', ['groups' => "contactLink"]);


        return new JsonResponse($contactLinkJson, JsonResponse::HTTP_OK, [], true);
    }
}
