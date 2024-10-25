<?php

namespace App\Controller;

use App\Entity\ContactLinkType;
use App\Repository\ContactLinkTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/contact-link-type')]

class ContactLinkTypeController extends AbstractController
{
    #[Route(name: 'api_contact_link_type_index', methods: ["GET"])]
    public function getAll(ContactLinkTypeRepository $contactLinkTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $contactLinkTypeList = $contactLinkTypeRepository->findAll();

        $contactLinkTypeJson = $serializer->serialize($contactLinkTypeList, 'json', ['groups' => "contactLinkType"]);


        return new JsonResponse($contactLinkTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_contact_link_type_show', methods: ["GET"])]
    public function get(ContactLinkType $contactLinkType, SerializerInterface $serializer): JsonResponse
    {
        $contactLinkTypeJson = $serializer->serialize($contactLinkType, 'json', ['groups' => "contactLinkType"]);

        return new JsonResponse($contactLinkTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: "/{id}", name: 'api_contact_link_type_edit', methods: ["PATCH"])]
    public function update(ContactLinkType $contactLinkType, UrlGeneratorInterface $urlGenerator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $updatedContactLinkType = $serializer->deserialize($request->getContent(), $contactLinkType::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contactLinkType]);

        $entityManager->persist($updatedContactLinkType);
        $entityManager->flush();
//        $contactLinkTypeJson = $serializer->serialize($updatedContactLinkType, 'json', ['groups' => "contactLinkType"]);
        $location = $urlGenerator->generate("api_contact_link_type_show", ['id' => $updatedContactLinkType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }
    #[Route(path: "/{id}", name: 'api_contact_link_type_delete', methods: ["DELETE"])]
    public function delete(ContactLinkType $contactLinkType, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($contactLinkType);
        } else {
            $contactLinkType->setStatus("off");
            $entityManager->persist($contactLinkType);
        }

        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }


}
