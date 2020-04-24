<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Entity\Panier;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="compte")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(UtilisateurType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($user);
            $pdo->flush();
        }

        $paniers = $pdo->getRepository(Panier::class)->findBy(
            array('utilisateur' =>  $user,
                    'etat' => true));

        $contenus = $pdo->getRepository(Contenu::class)->findBy(
                    array('panier' => $paniers));

        return $this->render('compte/index.html.twig', [
            'user' => $user,
            'form_edit' => $form->createView(),
            'paniers' => $paniers,
            'contenus' => $contenus,
        ]);
    }
}
