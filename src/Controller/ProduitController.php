<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ContenuType;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/{id}", name="produit")
     */
    public function index(Produit $produit, Request $request)
    {
        
        $pdo = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProduitType::class, $produit);

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $panier = $pdo->getRepository(Panier::class)->findOneBy(array('utilisateur' => $user, 'etat' => false));

        $contenu = new Contenu();
        $contenu->setProduit($produit);
        $contenu->setPanier($panier);
        $contenu->setDate(new \DateTime('now'));
      
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($produit);
            $pdo->flush();
        }

        $formcontenu = $this->createForm(ContenuType::class, $contenu);

        $formcontenu->handleRequest($request);
        if($formcontenu->isSubmitted() && $formcontenu->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($contenu);
            $em->flush();
        }

        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
            'panier' => $panier,
            'form_edit' => $form->createView(),
            'form_add' => $formcontenu->createView(),
        ]);
    }

    /**
     * @Route ("produit/delete/{id}", name="delete_produit")
     */

    public function delete(Produit $produit=null){

        if($produit !=null){

            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($produit);
            $produit->supprphoto();
            $pdo->flush();
        }
        return $this->redirectToRoute('accueil');
    }
    
}
