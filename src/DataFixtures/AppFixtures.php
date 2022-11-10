<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Creation d'une vingtaine de classe
        $listClasse = [];
        for ($i = 0; $i < 3; $i++) {
            $classe = new Classe;
            $classe->setName('Classe num ' . $i);
            $manager->persist($classe);
            $listClasse[] = $classe;
        }

        // Creation des personnes
        for ($i = 0; $i < 25; $i++) {
            $personne = new Personne;
            $personne->setFirstName("Personne " . $i);
            $personne->setLastName("Nom " . $i);
            $personne->setClasse($listClasse[array_rand($listClasse)]);
            $manager->persist($personne);
        }

        $manager->flush();
    }
}
