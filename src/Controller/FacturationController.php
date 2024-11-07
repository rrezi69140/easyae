<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Facturation;
use App\Repository\ContratRepository;
use App\Repository\FacturationModelRepository;
use App\Repository\FacturationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/facturation')]
class FacturationController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    #[Route(name: 'api_facturation_index', methods: ["GET"])]
    #[IsGranted("ROLE_ADMIN", message: "not authorized")]
    public function getAll(FacturationRepository $facturationRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {

        $idCache = "getAllFacturations";
        $facturationJson = $cache->get($idCache, function (ItemInterface $item) use ($facturationRepository, $serializer) {
            $item->tag("facturation");
            $item->tag("contrat");
            $item->tag("model");
            $facturationList = $facturationRepository->findAll();
            $facturationJson = $serializer->serialize($facturationList, 'json', ['groups' => "facturation"]);
            
            return $facturationJson;
        });

        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: '/{id}', name: 'api_facturation_show', methods: ["GET"])]
    public function get(Facturation $facturation, SerializerInterface $serializer): JsonResponse
    {
        $facturationJson = $serializer->serialize($facturation, 'json', ['groups' => "facturation"]);

        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_facturation_new', methods: ["POST"])]
    public function create(Request $request, ContratRepository $contratRepository,FacturationModelRepository $modelRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache): JsonResponse
    {
        if (!$this->user) {
            return new JsonResponse(['message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = $request->toArray();
        $contrat = $contratRepository->find($data["contrat"]);
        $model = $modelRepository->find($data["model"]);
        $facturation = $serializer->deserialize($request->getContent(), Facturation::class, 'json', []);
        $facturation->setContrat($contrat)
            ->setModel($model)
            ->setStatus("on")
            ->setCreatedBy($this->user->getId())
            ->setUpdatedBy($this->user->getId())
        ;
        $entityManager->persist($facturation);
        $entityManager->flush();
        $cache->invalidateTags(["facturation"]);
        $facturationJson = $serializer->serialize($facturation, 'json', ['groups' => "facturation"]);
        return new JsonResponse($facturationJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: '/{id}', name: 'api_facturation_edit', methods: ["PATCH"])]
    public function update(TagAwareCacheInterface $cache,Facturation $facturation, Request $request, UrlGeneratorInterface $urlGenerator, ContratRepository $contratRepository,FacturationModelRepository $modelRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->user) {
            return new JsonResponse(['message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        
        $data = $request->toArray();
        if (isset($data["contrat"])) {
            $contrat = $contratRepository->find($data["contrat"]);
        }
        if (isset($data["model"])) {
            $model = $modelRepository->find($data["model"]);
        }

        $updateFacturation = $serializer->deserialize(data: $request->getContent(), type: Facturation::class, format:"json", context: [AbstractNormalizer::OBJECT_TO_POPULATE => $facturation]);
        $updateFacturation->setContrat($contrat ?? $updateFacturation->getcontrat())->setModel($model ?? $updateFacturation->getModel())
            ->setStatus("on")
            ->setUpdatedBy($this->user->getId());

        $entityManager->persist(object: $updateFacturation);
        $entityManager->flush();
        $cache->invalidateTags(["facturation"]);
        $location = $urlGenerator->generate("api_facturation_show", ['id' => $updateFacturation->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $facturationJson = $serializer->serialize(data: $updateFacturation, format: "json", context: ["groups" => "facturation"]);
        return new JsonResponse($facturationJson, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }

    #[Route(path: '/{id}', name: 'api_facturation_delete', methods: ["DELETE"])]
    public function delete(TagAwareCacheInterface $cache, Facturation $facturation, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->user) {
            return new JsonResponse(['message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $data = $request->toArray();
        if (isset($data['force']) && $data['force'] === true) {
            if (!$this->isGranted("ROLE_ADMIN")) {
                return new JsonResponse(["error" => "Hanhanhaaaaan vous n'avez pas dit le mot magiiiiqueeuuuuuh"], JsonResponse::HTTP_FORBIDDEN);
            }
            $entityManager->remove(object: $facturation);
        } else {
            $facturation->setStatus("off");
            $entityManager->persist(object: $facturation);
        }
        $facturation->setStatus("off");
        $facturation->setUpdatedBy($this->user->getId());

        $entityManager->persist(object: $facturation);
        $entityManager->flush();
        $cache->invalidateTags(["facturation"]);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, []);
    }
}
