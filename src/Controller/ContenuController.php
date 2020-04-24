<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContenuController extends AbstractController
{
    /**
     * @Route("/contenu/{id}", name="contenu")
     */
    public function index(Panier $panier)
    {

        $pdo = $this->getDoctrine()->getManager();
        $contenus =  $pdo->getRepository(Contenu::class)->findby(array('panier'=> $panier));


        return $this->render('contenu/index.html.twig', [
            'panier' => $panier,
            'contenus' => $contenus
        ]);
    }
}
