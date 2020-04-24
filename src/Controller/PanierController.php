<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Entity\Panier;
use App\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(Request $request)
    {

        $pdo = $this->getDoctrine()->getManager();

        $panier = new Panier();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $panier->setUtilisateur($user);

        $form = $this->createForm(PanierType::class, $panier);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $panier = $form->getData();
            $pdo->persist($panier);
            $pdo->flush();
            
        }

        $paniers = $pdo->getRepository(Panier::class)->findBy(
                    array('utilisateur' =>  $user,
                            'etat' => false));

        $contenus = $pdo->getRepository(Contenu::class)->findBy(
                    array('panier' => $paniers));

        return $this->render('panier/index.html.twig', [
            'form_ajout' => $form->createView(),
            'paniers' => $paniers,
            'contenus' => $contenus,
        ]);
    }

    /**
     * @Route("/panier/{id}", name="valid_panier")
     */
    public function valid(Request $request, Panier $panier)
    {

        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);
        $panier->setEtat(true);
        $panier->setDate(new \DateTime('now'));
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('panier');

        return $this->render('panier/index.html.twig', [
            'form_valid' => $form->createView(),
        ]);
    }

    
    
}
