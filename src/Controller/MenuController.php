<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Enclos;
use App\Entity\Espace;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function _menu(ManagerRegistry $doctrine): Response
    {
        return $this->render('menu/index.html.twig', [
            'espace'=>$doctrine->getRepository(Espace::class)->findAll()
        ]);
    }
}
