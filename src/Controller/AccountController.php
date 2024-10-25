<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/account')]

class AccountController extends AbstractController
{
    #[Route(name: 'api_account_index', methods: ["GET"])]
    public function getAll(AccountRepository $accountRepository, SerializerInterface $serializer): JsonResponse
    {
        $accountList = $accountRepository->findAll();

        $accountJson = $serializer->serialize($accountList, 'json', ['groups' => "account"]);


        return new JsonResponse($accountJson, JsonResponse::HTTP_OK, [], true);
    }
    #[Route(path: '/{id}', name: 'api_account_show', methods: ["GET"])]
    public function get(Account $account, SerializerInterface $serializer): JsonResponse
    {
        $accountJson = $serializer->serialize($account, 'json', ['groups' => "account"]);
        return new JsonResponse($accountJson, JsonResponse::HTTP_OK, [], true);
    }

    #[Route(name: 'api_account_new', methods: ["POST"])]
    public function create(Request $request, ClientRepository $clientRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = $request->toArray();
        $client = $clientRepository->find($data["client"]);
        $account = $serializer->deserialize($request->getContent(), Account::class, 'json', []);
        $account->setClient($client)
            ->setStatus("on")
        ;
        $entityManager->persist($account);
        $entityManager->flush();
        $accountJson = $serializer->serialize($account, 'json', ['groups' => "account"]);
        return new JsonResponse($accountJson, JsonResponse::HTTP_OK, [], true);
    }

}
