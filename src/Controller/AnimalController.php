<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\AnimalSupprimerType;
use App\Form\AnimalType;
use App\Form\EnclosSupprimerType;
use App\Form\EnclosType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('/animals/ajouter/{error}', name: 'app_animal_ajouter')]
    public function ajouter($error, ManagerRegistry $doctrine, Request $request): Response
    {

        $animal = new Animal();

        $form = $this->createForm(AnimalType::class, $animal);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sexe = $animal->getSexe();
            $sterile = $animal->isSterile();
            $dateArrivee = $animal->getDateArrivee();
            $dateDepart = $animal->getDateDepart();
            $dateNaissance = $animal->getDateNaissance();


            if ($sexe == "Non défini") {
                if ($sterile == 1) {
                    $error = "1";
                    return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
                }
            }
            if ($dateArrivee != null && $dateDepart != null) {
                if ($dateArrivee == null && $dateDepart != null) {
                    $error = "2";
                    return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
                }

                if ($dateArrivee->getTimestamp() > $dateDepart->getTimestamp()) {
                    $error = "3";
                    return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
                }
            }
            if ($dateArrivee->getTimestamp() > $dateNaissance->getTimestamp()) {
                $error = "4";
                return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
            }


            $em = $doctrine->getManager();

            $em->persist(($animal));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("animal/ajouter.html.twig", [
            "formulaire" => $form->createView(),
            "error" => $error
        ]);
    }

    #[Route('/animal/modifier/{id}{error}', name: 'app_animal_modifier')]
    public function modifier($id, $error, ManagerRegistry $doctrine, Request $request): Response
    {

        $animal = $doctrine->getRepository(Animal::class)->find($id);


        if (!$animal) {
            throw $this->createNotFoundException("Pas d'animal avec l'id $id");
        }


        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $sexe = $animal->getSexe();
            $sterile = $animal->isSterile();
            $dateArrivee = $animal->getDateArrivee();
            $dateDepart = $animal->getDateDepart();
            $dateNaissance = $animal->getDateNaissance();


            if ($sexe == "Non défini") {
                if ($sterile == 1) {
                    $error = "1";
                    return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
                }
            }
            if ($dateArrivee != null && $dateDepart != null) {
                if ($dateArrivee == null && $dateDepart != null) {
                    $error = "2";
                    return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
                }

                if ($dateArrivee->getTimestamp() > $dateDepart->getTimestamp()) {
                    $error = "3";
                    return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
                }
            }
            if ($dateArrivee->getTimestamp() > $dateNaissance->getTimestamp()) {
                $error = "4";
                return $this->redirectToRoute("app_animal_ajouter", ["error" => $error]);
            }

            $em = $doctrine->getManager();

            $em->persist(($animal));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("animal/modifier.html.twig", [
            "animal" => $animal,
            "formulaire" => $form->createView(),
            "error" => $error
        ]);
    }

    #[Route('/animal/supprimer/{id}', name: 'app_animal_supprimer')]
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {

        $animal = $doctrine->getRepository(Animal::class)->find($id);


        if (!$animal) {
            throw $this->createNotFoundException("Pas d'animal avec l'id $id");
        }


        $form = $this->createForm(AnimalSupprimerType::class, $animal);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $doctrine->getManager();

            $em->remove(($animal));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("animal/supprimer.html.twig", [
            "animal" => $animal,
            "formulaire" => $form->createView()
        ]);

    }

}
