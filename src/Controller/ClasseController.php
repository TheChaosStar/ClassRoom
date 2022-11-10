<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
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

    #[Route('/api/classe/{id}', name: 'app_create_classe', methods: ['POST'])]
    public function createClasse(
        Request $request, SerializerInterface $serializer,
        EntityManagerInterface $em, UrlGenerator $urlGenerator
    ): JsonResponse
    {
        $classe = $serializer->deserialize($request->getContent(), Classe::class, 'json');

        $em->persist($classe);
        $em->flush();

        $jsonClasse = $serializer->serialize($classe, 'json', ['groups' => 'getClasse']);
        $location = $urlGenerator->generate('app_one_classe', ['id' => $classe->getId()], UrlGenerator::ABSOLUTE_URL);
        
        return new JsonResponse($jsonClasse, Response::HTTP_CREATED, ["Location" => $location], true);
    }

}
