<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/contact')]

class ContactController extends AbstractController
{
    #[Route(name: 'api_contact_index', methods: ["GET"])]
    public function getAll(ContactRepository $contactRepository, SerializerInterface $serializer): JsonResponse
    {
        $contactList = $contactRepository->findAll();

        $contactJson = $serializer->serialize($contactList, 'json', ['groups' => "contact"]);


        return new JsonResponse($contactJson, JsonResponse::HTTP_OK, [], true);
    }
    
    
    #[Route(path: '/{id}', name: 'api_contact_show', methods: ["GET"])]
    public function get(Contact $contact, SerializerInterface $serializer): JsonResponse
    {
        $contactJson = $serializer->serialize($contact, 'json', ['groups' => "contact"]);
        
        return new JsonResponse($contactJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_contact_new', methods: ["POST"])]
    public function create(Request $request, ContactRepository $contactRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();

        $contact = $contactRepository->find($data["contact"]);

        $contact = $serializer->deserialize($request->getContent(), Contact::class, 'json', []);
        $contact->setClient($client)
            ->setStatus("on");

        $entityManager->persist($contact);
        $entityManager->flush();

        $contactJson = $serializer->serialize($contact, 'json', ['groups' => "contact"]);

        return new JsonResponse($contactJson, JsonResponse::HTTP_CREATED, [], true);
    }


    #[Route(path: "/{id}", name: 'api_contact_edit', methods: ["PATCH"])]
    public function update(Contact $contact, UrlGeneratorInterface $urlGenerator, Request $request,ContactRepository $contactRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();

        if (isset($data['contact'])) {

            $contact = $contactRepository->find($data["contact"]);
        }

        $updatedcontact = $serializer->deserialize($request->getContent(), Contact::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contact]);
        $updatedcontact
            ->setClient($client ?? $updatedcontact->getClient())
            ->setStatus("on");

        $entityManager->persist($updatedcontact);
        $entityManager->flush();

        $contactJson = $serializer->serialize($updatedcontact, 'json', ['groups' => "contact"]);

        $location = $urlGenerator->generate("api_contact_show", ['id' => $updatedcontact->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }


    #[Route(path: "/{id}", name: 'api_contact_delete', methods: ["DELETE"])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($contact);

        } else {
            $contact
                ->setStatus("off");
            $entityManager->persist($contact);
        }

        $entityManager->flush();
        
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
