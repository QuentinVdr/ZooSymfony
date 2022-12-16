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
use function PHPUnit\Framework\isNull;

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

    #[Route('/espace/ajouter/{error}', name: 'app_espace_ajouter')]
    public function ajouter($error, ManagerRegistry $doctrine, Request $request): Response
    {
        $espace= new Espace();
        $form=$this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $dateOuverture = $espace->getDateOuverture();
            $dateFermeture = $espace->getDateFermeture();

            if ($dateOuverture != null && $dateFermeture != null){
                if($dateOuverture == null && $dateFermeture != null){
                    $error = "1";
                    return $this->redirectToRoute("app_espace_ajouter", ["error" => $error]);
                }

                if ($dateOuverture->getTimestamp() > $dateFermeture->getTimestamp()){
                    $error = "2";
                    return $this->redirectToRoute("app_espace_ajouter", ["error" => $error]);
                }
            }
            if ($dateOuverture == null && $dateFermeture != null){
                $error = "3";
                return $this->redirectToRoute("app_espace_ajouter", ["error" => $error]);
            }

            $em=$doctrine->getManager();

            $em->persist(($espace));

            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("espace/ajouter.html.twig",[
            "formulaire"=>$form->createView(),
            "error"=>$error
        ]);
    }

    #[Route('/espace/modifier/{id}{error}', name: 'app_espace_modifier')]
    public function modifier($id, $error, ManagerRegistry $doctrine, Request $request): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);

        if (!$espace){
            throw $this->createNotFoundException("Pas d'espace avec l'id $id");
        }

        $form=$this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $dateOuverture = $espace->getDateOuverture();
            $dateFermeture = $espace->getDateFermeture();

            if ($dateOuverture != null && $dateFermeture != null){
                if($dateOuverture == null && $dateFermeture != null){
                    $error = "1";
                    return $this->redirectToRoute("app_espace_modifier", ["id" => $id, "error" => $error]);
                }

                if ($dateOuverture->getTimestamp() > $dateFermeture->getTimestamp()){
                    $error = "2";
                    return $this->redirectToRoute("app_espace_modifier", ["id" => $id, "error" => $error]);
                }
            }
            if ($dateOuverture == null && $dateFermeture != null){
                $error = "3";
                return $this->redirectToRoute("app_espace_modifier", ["id" => $id, "error" => $error]);
            }

            $em=$doctrine->getManager();

            $em->persist(($espace));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("espace/modifier.html.twig",[
            "formulaire"=>$form->createView(),
            "espace"=>$espace,
            "error"=>$error
        ]);

    }

    #[Route('/espace/supprimer/{id}{error}', name: 'app_espace_supprimer')]
    public function supprimer($id, $error, ManagerRegistry $doctrine, Request $request): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);


        if (!$espace){
            throw $this->createNotFoundException("Pas d'espace avec l'id $id");
        }


        $form=$this->createForm(EspaceSupprimerType::class, $espace);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $enclos = $espace->getEnclos();

            foreach ($enclos->getIterator() as $enclos){
                if (count($enclos->getAnimals()) != 0){
                    $error = "1";
                }
            }
            if ($error == "1"){
                return $this->redirectToRoute("app_espace_supprimer", ["id" => $id, "error" => $error]);
            }

            $em=$doctrine->getManager();

            $em->remove(($espace));


            $em->flush();

            return $this->redirectToRoute("app_espace");
        }

        return $this->render("espace/supprimer.html.twig",[
            "espace"=>$espace,
            "error"=>$error,
            "formulaire"=>$form->createView()
        ]);

    }

    #[Route('/espace/animal/{id}', name: 'app_espace_animal')]
    public function voirAnimal($id, ManagerRegistry $doctrine): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);
        $enclos = $doctrine->getRepository(Enclos::class)->findBy(array('espace' => $espace));
        $animal = $doctrine->getRepository(Animal::class)->findBy(array('enclos' => $enclos));
        //si on n'a rien trouvé -> 404
        if (!$espace) {
            throw $this->createNotFoundException("Aucun espace avec l'id $id");
        }if (!$enclos) {
            throw $this->createNotFoundException("Aucun enclos trouvé");
        }
        if (!$enclos) {
            throw $this->createNotFoundException("Enclos non trouvé");
        }

        return $this->render('espace/voirAnimal.html.twig', [
            'espace' => $espace,
            "animal" => $animal
        ]);
    }


}
