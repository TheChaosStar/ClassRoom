<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $jsonClasseList = $serializer->serialize($classeList, 'json', ['groups' => 'getClasse']);
        return new JsonResponse($jsonClasseList, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/classe/{id}', name: 'app_one_classe', methods: ["GET"])]
    public function getOneClasse(Classe $classe, SerializerInterface $serializer): JsonResponse
    {
        $jsonClasse = $serializer->serialize($classe, 'json', ['groups' => 'getClasse']);
        return new JsonResponse($jsonClasse, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/classe/{id}', name: 'app_delete_classe', methods: ['DELETE'])]
    public function deleteClasse(Classe $classe, EntityManagerInterface $em): Response
    {
        $em->remove($classe);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
