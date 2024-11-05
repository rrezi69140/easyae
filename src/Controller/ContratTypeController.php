<?php

namespace App\Controller;

use App\Entity\ContratType;
use App\Repository\ContratTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api/contrat-type')]

class ContratTypeController extends AbstractController
{
    #[Route(name: 'api_contrat_type_index', methods: ["GET"])]
    public function getAll(ContratTypeRepository $contratTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $contratTypeList = $contratTypeRepository->findAll();

        $contratTypeJson = $serializer->serialize($contratTypeList, 'json', ['groups' => "contratType"]);

        return new JsonResponse($contratTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_contrat_type_show', methods: ["GET"])]
    public function get(ContratType $contratType, SerializerInterface $serializer): JsonResponse
    {
        $contratTypeJson = $serializer->serialize($contratType, 'json', ['groups' => "contratType"]);

        return new JsonResponse($contratTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_contrat_type_new', methods: ["POST"])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $contratType = $serializer->deserialize($request->getContent(), ContratType::class, 'json', []);
        $contratType->setStatus("on");
        $entityManager->persist($contratType);
        $entityManager->flush();
        $contratTypeJson = $serializer->serialize($contratType, 'json', ['groups' => "contratType"]);
        return new JsonResponse($contratTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: "/{id}", name: 'api_contrat_type_edit', methods: ["PATCH"])]
    public function update(ContratType $contratType, UrlGeneratorInterface $urlGenerator, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $updatedContratType = $serializer->deserialize($request->getContent(), ContratType::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contratType]);
        $updatedContratType
            ->setStatus("on")
        ;

        $entityManager->persist($updatedContratType);
        $entityManager->flush();
        $location = $urlGenerator->generate("api_contrat_type_show", ['id' => $updatedContratType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }

    #[Route(path: "/{id}", name: 'api_contrat_type_delete', methods: ["DELETE"])]
    public function delete(ContratType $contratType, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($contratType);
        } else {
            $contratType
                ->setStatus("off")
            ;
            $entityManager->persist($contratType);
        }

        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
