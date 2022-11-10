<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClasseController extends AbstractController
{
    #[Route('/api/classes', name: 'app_all_classes', methods: ["GET"])]
    public function getAllClasses(ClasseRepository $classeRepository, SerializerInterface $serializer): JsonResponse
    {
        $classeList = $classeRepository->findAll();
        $jsonClasseList = $serializer->serialize($classeList, 'json');
        return new JsonResponse($jsonClasseList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/classe/{id}', name: 'app_one_classe', methods: ["GET"])]
    public function getOneClasse(Classe $classe, SerializerInterface $serializer): JsonResponse
    {
        $jsonClasse = $serializer->serialize($classe, 'json');
        return new JsonResponse($jsonClasse, Response::HTTP_OK, ['accept' => 'json'], true);
    }
}
