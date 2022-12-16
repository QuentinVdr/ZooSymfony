<?php

namespace App\Controller;


use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\EnclosSupprimerType;
use App\Form\EnclosType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnclosController extends AbstractController
{
    #[Route('/enclos/{id}', name: 'app_enclos')]
    public function index($id, ManagerRegistry $doctrine): Response
    {

        $espace = $doctrine->getRepository(Espace::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$espace) {
            throw $this->createNotFoundException("Aucun espace avec l'id $id");
        }

        return $this->render('enclos/index.html.twig', [
            'espace' => $espace,
            'enclos' => $espace->getEnclos()
        ]);
    }

    #[Route('/enclo/modifier/{id}', name: 'app_enclos_modifier')]
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {

        $enclos = $doctrine->getRepository(Enclos::class)->find($id);


        if (!$enclos) {
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }


        $form = $this->createForm(EnclosType::class, $enclos);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quarantaine = $enclos->isQuarantaine();

            if ($quarantaine){
                $animaux = $enclos->getAnimals();
                foreach ($animaux->getIterator() as $animal){
                    $animal->setQuarantaine(true);
                }
            }

            $em = $doctrine->getManager();

            $em->persist(($enclos));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("enclos/modifier.html.twig",[
            "enclos"=>$enclos,
            "formulaire"=>$form->createView()
        ]);
    }


    #[Route('/enclo/ajouter', name: 'app_enclos_ajouter')]
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {

        $enclos= new Enclos();

        $form=$this->createForm(EnclosType::class, $enclos);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();

            $em->persist(($enclos));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("enclos/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    #[Route('/enclo/supprimer/{id}{error}', name: 'app_enclos_supprimer')]
    public function supprimer($id, $error,ManagerRegistry $doctrine, Request $request): Response
    {
        $enclos = $doctrine->getRepository(Enclos::class)->find($id);

        if (!$enclos){
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        $form=$this->createForm(EnclosSupprimerType::class, $enclos);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if(count($enclos->getAnimals()) != 0 ){
                $error = "1";
                return $this->redirectToRoute("app_enclos_supprimer", ["id"=>$id, "error"=>$error]);
            }

            $em=$doctrine->getManager();

            $em->remove(($enclos));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("enclos/supprimer.html.twig",[
            "enclos"=>$enclos,
            "error"=>$error,
            "formulaire"=>$form->createView()
        ]);

    }

    #[Route('/enclo/animal/{id}', name: 'app_enclos_animal')]
    public function voirAnimal($id, ManagerRegistry $doctrine): Response
    {
        $enclo = $doctrine->getRepository(Enclos::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$enclo) {
            throw $this->createNotFoundException("Aucun enclos avec l'id $id");
        }

        return $this->render('enclos/voirAnimal.html.twig', [
            'enclo' => $enclo,
            "animal" => $enclo->getAnimals()
        ]);
    }


}
