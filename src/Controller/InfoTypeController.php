<?php

namespace App\Controller;

use App\Entity\InfoType;
use App\Repository\InfoTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
#[Route('/api/info-type')]
class InfoTypeController extends AbstractController
{
    #[Route(name: 'api_InfoType_index', methods: ["GET"])]
    public function getAll(InfoTypeRepository $infoTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $infoTypeList = $infoTypeRepository->findAll();

        $infoTypeJson = $serializer->serialize($infoTypeList, 'json', ['groups' => "infoType"]);


        return new JsonResponse($infoTypeJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_infoType_show', methods: ["GET"])]
    public function get(InfoType $infoType, SerializerInterface $serializer): JsonResponse
    {
        // $infoTypetList = $infoTypeRepository->find($id);

        $infoTypeJson = $serializer->serialize($infoType, 'json', ['groups' => "infoType"]);


        return new JsonResponse($infoTypeJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_infoType_new', methods: ["POST"])]
    public function create(Request $request, InfoTypeRepository $infoTypeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
     
        $infoType = $serializer->deserialize($request->getContent(), InfoType::class, 'json', []);
        $entityManager->persist($infoType);
        $entityManager->flush();
        $infoTypeJson = $serializer->serialize($infoType, 'json', ['groups' => "infoType"]);
        return new JsonResponse($infoTypeJson, JsonResponse::HTTP_CREATED, [], true);
    }

    #[Route(path: "/{id}", name: 'api_infoType_edit', methods: ["PATCH"])]
    public function update(InfoType $infoType, UrlGeneratorInterface $urlGenerator, Request $request, InfoTypeRepository $infoTypeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();


        $updatedInfoType = $serializer->deserialize($request->getContent(), InfoType::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $infoType]);
        $updatedInfoType
            ->setStatus("on")
        ;

        $entityManager->persist($updatedInfoType);
        $entityManager->flush();
        $infoTypeJson = $serializer->serialize($updatedInfoType, 'json', ['groups' => "infoType"]);
        $location = $urlGenerator->generate("api_account_show", ['id' => $updatedInfoType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, ["Location" => $location]);
    }
    #[Route(path: "/{id}", name: 'api_infoType_delete', methods: ["DELETE"])]
    public function delete( InfoType $infoType,UrlGeneratorInterface $urlGenerator, Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
   
    
    {
        $data = $request->toArray();
    
        if (isset($data['force']) && $data['force'] === true) {
            $entityManager->remove($infoType);
            

        } else {
            $infoType
                ->setStatus("off")
            ;

            $entityManager->persist($infoType);
        }



        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
