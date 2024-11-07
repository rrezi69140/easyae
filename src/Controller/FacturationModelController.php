<?php

namespace App\Controller;
use App\Repository\FacturationModelRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\FacturationModel;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/facturation-model')]

class FacturationModelController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    #[Route(name: 'api_facturation_model_index', methods: ["GET"])]
    public function getAll(FacturationModelRepository $facturationModelRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        $idCache = "getAllFacturationsModels";
        $facturationModelJson = $cache->get($idCache, function (ItemInterface $item) use ($facturationModelRepository, $serializer) {
            $item->tag("facturationModel");
            $item->tag("client");
            $facturationModelList = $facturationModelRepository->findAll();
            $facturationModelJson = $serializer->serialize($facturationModelList, 'json', ['groups' => "facturationModel"]);

            return $facturationModelJson;

        });


        return new JsonResponse($facturationModelJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_facturation_model_show', methods: ["GET"])]
    public function get(FacturationModel $facturationModel, SerializerInterface $serializer): JsonResponse
    {
        $facturationModelJson = $serializer->serialize($facturationModel, 'json', ['groups' => "facturationModel"]);

        return new JsonResponse($facturationModelJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_facturation_model_new', methods: ["POST"])]
    public function create(tagAwareCacheInterface $cache, Request $request, ClientRepository $clientRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->user) {
            return new JsonResponse(['message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $facturationModel = $serializer->deserialize($request->getContent(), FacturationModel::class, 'json', []);
        $facturationModel->setClient($client)
            ->setStatus("on")
            ->setCreatedBy($this->user->getId())
            ->setUpdatedBy($this->user->getId())
        ;
        $entityManager->persist($facturationModel);
        $entityManager->flush();
        $cache->invalidateTags(["facturationModel"]);
        $facturationModelJson = $serializer->serialize($facturationModel, 'json', ['groups' => "facturationModel"]);
        return new JsonResponse($facturationModelJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: "/{id}", name: 'api_facturation_model_edit', methods: ["PATCH"])]
    public function update(tagAwareCacheInterface $cache ,facturationModel $facturationModel, UrlGeneratorInterface $urlGenerator, Request $request, ClientRepository $clientRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->user) {
            return new JsonResponse(['message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = $request->toArray();
        if (isset($data['client'])) {

            $client = $clientRepository->find($data["client"]);
        }


        $updatedFacturationModel = $serializer->deserialize($request->getContent(), Account::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $facturationModel]);
        $updatedFacturationModel
            ->setClient($client ?? $updatedFacturationModel->getClient())
            ->setStatus("on")
            ->setUpdatedBy($this->user->getId())
        ;

        $entityManager->persist($updatedFacturationModel);
        $entityManager->flush();
        $cache->invalidateTags(tag: ["facturationModel","client"]);
        $facturationModelJson = $serializer->serialize($updatedFacturationModel, 'json', ['groups' => "facturationModel"]);
        $location = $urlGenerator->generate("api_facturation_model_show", ['id' => $updatedFacturationModel->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }

    #[Route(path: "/{id}", name: 'api_facturation_model_delete', methods: ["DELETE"])]
    public function delete(TagAwareCacheInterface $cache, FacturationModel $facturationModel, UrlGeneratorInterface $urlGenerator, Request $request, ClientRepository $clientRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($facturationModel);


        } else {
            $facturationModel
                ->setStatus("off")
            ;

            $entityManager->persist($facturationModel);
        }



        $entityManager->flush();
        $cache->invalidateTags(["facturationModel"]);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}