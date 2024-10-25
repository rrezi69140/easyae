<?php

namespace App\Controller;

use App\Repository\ContratTypeRepository;
use App\Repository\ClientRepository;
use App\Entity\Contrat;
use App\Repository\ContratRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/contrat')]

class ContratController extends AbstractController
{
    #[Route(name: 'api_contrat_index', methods: ["GET"])]
    public function getAll(ContratRepository $contratRepository, SerializerInterface $serializer): JsonResponse
    {
        $contratList = $contratRepository->findAll();

        $contratJson = $serializer->serialize($contratList, 'json', ['groups' => "contrat"]);


        return new JsonResponse($contratJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(path: "/{id}", name: 'api_contrat_show', methods: ["GET"])]
    public function get(Contrat $contrat, SerializerInterface $serializer): JsonResponse
    {

        $contratJson = $serializer->serialize($contrat, 'json', ['groups' => "contrat"]);

        return new JsonResponse($contratJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_contrat_new', methods: ["POST"])]
    public function create(Request $request, clientRepository $clientRepository, ContratTypeRepository $typeRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        // dd($data);
        $contrat = $serializer->deserialize($request->getContent(), Contrat::class, 'json', []);
        $client = $clientRepository->find($data["client"]);
        $type = $typeRepository->find($data["contratType"]);
        $start = new DateTime($data["startAt"]);
        $end = new DateTime($data["endAt"]);
        $done = $data["is_done"];
        $contrat->setClient($client)
            ->setType($type)
            ->setDone($done)
            ->setStartAt($start)
            ->setEndAt($end)
        ;
        $entityManager->persist($contrat);
        $entityManager->flush();

        $contratJson = $serializer->serialize($contrat, 'json', ['groups' => "contrat"]);
        return new JsonResponse($contratJson, JsonResponse::HTTP_OK, [], true);
    }
}
