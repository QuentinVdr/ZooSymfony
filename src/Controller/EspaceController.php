<?php

namespace App\Controller;


use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\EspaceSupprimerType;
use App\Form\EspaceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceController extends AbstractController
{
    #[Route('/', name: 'app_espace')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Espace::class);
        $espace = $repo->findAll();

        return $this->render('espace/index.html.twig', [
            'espace' => $espace
        ]);
    }

    #[Route('/espace/ajouter', name: 'app_espace_ajouter')]
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $espace= new Espace();
        $form=$this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();

            $em->persist(($espace));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("espace/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    #[Route('/espace/modifier/{id}', name: 'app_espace_modifier')]
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {

        $espace = $doctrine->getRepository(Espace::class)->find($id);

        if (!$espace){
            throw $this->createNotFoundException("Pas d'espace avec l'id $id");
        }

        $form=$this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();

            $em->persist(($espace));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("espace/modifier.html.twig",[
            "espace"=>$espace,
            "formulaire"=>$form->createView()
        ]);

    }

    #[Route('/espace/supprimer/{id}', name: 'app_espace_supprimer')]
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);


        if (!$espace){
            throw $this->createNotFoundException("Pas d'espace avec l'id $id");
        }


        $form=$this->createForm(EspaceSupprimerType::class, $espace);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em=$doctrine->getManager();

            $em->remove(($espace));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("espace/supprimer.html.twig",[
            "espace"=>$espace,
            "formulaire"=>$form->createView()
        ]);

    }

    #[Route('/espace/animal/{id}', name: 'app_espace_animal')]
    public function voirAnimal($id, ManagerRegistry $doctrine): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$espace) {
            throw $this->createNotFoundException("Aucun espace avec l'id $id");
        }

        return $this->render('espace/voirAnimal.html.twig', [
            'espace' => $espace,
            "animal" => $espace->getEnclos()->add(Animal::class)
        ]);
    }


}
