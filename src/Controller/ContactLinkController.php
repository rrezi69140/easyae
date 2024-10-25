<?php

namespace App\Controller;

use App\Entity\ContactLink;
use App\Repository\ContactLinkRepository;
use App\Repository\ContactLinkTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/contact-link')]

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
        $contactLinkJson = $serializer->serialize($contactLink, 'json', ['groups' => "contactLink"]);

        return new JsonResponse($contactLinkJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_contact_link_new', methods: ["POST"])]
    public function create(Request $request, ContactLinkTypeRepository $contactLinkTypeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $contactLinkType = $contactLinkTypeRepository->find($data["contactLinkType"]);
        $contactLink = $serializer->deserialize($request->getContent(), ContactLink::class, 'json', []);
        $contactLink->setClient($contactLinkType)
            ->setStatus("on")
        ;
        $entityManager->persist($contactLink);
        $entityManager->flush();
        $contactLinkJson = $serializer->serialize($contactLink, 'json', ['groups' => "contactLink"]);
        return new JsonResponse($contactLinkJson, JsonResponse::HTTP_OK, [], true);
    }
}
