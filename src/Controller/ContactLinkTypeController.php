<?php

namespace App\Controller;

use App\Entity\ContactLinkType;
use App\Repository\ContactLinkTypeRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/contact-link-type')]

class ContactLinkTypeController extends AbstractController
{
    #[Route(name: 'api_contact_link_type_index', methods: ["GET"])]
    public function getAll(ContactLinkTypeRepository $contactLinkTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $contactLinkTypeList = $contactLinkTypeRepository->findAll();

        $contactLinkTypeJson = $serializer->serialize($contactLinkTypeList, 'json', ['groups' => "contact_link_type"]);


        return new JsonResponse($contactLinkTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_contact_link_type_show', methods: ["GET"])]
    public function get(ContactLinkType $contactLinkType, SerializerInterface $serializer): JsonResponse
    {
        $contactLinkTypeJson = $serializer->serialize($contactLinkType, 'json', ['groups' => "contact_link_type"]);


        return new JsonResponse($contactLinkTypeJson, JsonResponse::HTTP_OK, [], true);
    }
}
