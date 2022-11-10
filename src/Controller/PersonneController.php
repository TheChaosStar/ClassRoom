<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PersonneController extends AbstractController
{
    #[Route('/api/personnes', name: 'app_all_personne', methods: ["GET"])]
    public function getAllPersonnes(PersonneRepository $personneRepository, SerializerInterface $serializer): JsonResponse
    {
        $personneList = $personneRepository->findAll();

        $jsonPersonneList = $serializer->serialize($personneList, 'json', ['groups' => 'getPersonne']);
        return new JsonResponse($jsonPersonneList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/classe/{id}', name: 'app_one_classe', methods: ["GET"])]
    public function getOneClasse(Personne $personne, SerializerInterface $serializer): JsonResponse
    {
        $jsonPersonne = $serializer->serialize($personne, 'json', ['groups' => 'getPersonne']);
        return new JsonResponse($jsonPersonne, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
