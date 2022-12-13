<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\AnimalSupprimerType;
use App\Form\AnimalType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{

    #[Route('/animal/modifier/{id}', name: 'app_animal_modifier')]
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {

        $animal = $doctrine->getRepository(Animal::class)->find($id);


        if (!$animal) {
            throw $this->createNotFoundException("Pas d'animal avec l'id $id");
        }


        $form = $this->createForm(AnimalType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();

            $em->persist(($animal));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("animal/modifier.html.twig",[
            "animal"=>$animal,
            "formulaire"=>$form->createView()
        ]);
    }


    #[Route('/animal/ajouter', name: 'app_animal_ajouter')]
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {

        $animal= new Animal();

        $form=$this->createForm(AnimalType::class, $animal);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();

            $em->persist(($animal));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("animal/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    #[Route('/animal/supprimer/{id}', name: 'app_animal_supprimer')]
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {

        $animal = $doctrine->getRepository(Animal::class)->find($id);


        if (!$animal){
            throw $this->createNotFoundException("Pas d'animal avec l'id $id");
        }


        $form=$this->createForm(AnimalSupprimerType::class, $animal);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){


            $em=$doctrine->getManager();

            $em->remove(($animal));


            $em->flush();

            return $this->redirectToRoute("app_animal");
        }

        return $this->render("animal/supprimer.html.twig",[
            "animal"=>$animal,
            "formulaire"=>$form->createView()
        ]);

    }
}
