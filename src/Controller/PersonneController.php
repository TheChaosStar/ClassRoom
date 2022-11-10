<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\ClasseRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    #[Route('/api/personne/{id}', name: 'app_one_personne', methods: ["GET"])]
    public function getOnePersonne(Personne $personne, SerializerInterface $serializer): JsonResponse
    {
        $jsonPersonne = $serializer->serialize($personne, 'json', ['groups' => 'getPersonne']);
        return new JsonResponse($jsonPersonne, Response::HTTP_OK, ['accept' => 'json'], true);
    }

    #[Route('/api/personne/{id}', name: 'app_delete_personne', methods: ['DELETE'])]
    public function deletePersonne(Personne $personne, EntityManagerInterface $em): Response
    {
        $em->remove($personne);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/personne', name: 'app_create_personne', methods: ['POST'])]
    public function createPersonne(
        Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator, ClasseRepository $classeRepository
    ): JsonResponse
    {
        $personne = $serializer->deserialize($request->getContent(), Personne::class, 'json');
        
        // Récuperation de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();

        // Récupération de l'idClasse. S'il n'est pas defini, alors on met -1 par défaut.
        $idClasse = $content['idClasse'] ?? -1;

        // On cherche l'auteur qui correspond et on l'assigne au personne.
        // Si "find" no trouver pas la classe, alors null sera retourné
        $personne->setClasse($classeRepository->find($idClasse));

        $em->persist($personne);
        $em->flush();

        $jsonPersonne = $serializer->serialize($personne, 'json', ['groups' => 'getPersonne']);
        $location = $urlGenerator->generate('app_one_personne', ['id' => $personne->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        
        return new JsonResponse($jsonPersonne, Response::HTTP_CREATED, ["Location" => $location], true);
    }
}
