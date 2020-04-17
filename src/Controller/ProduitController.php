<?php

namespace App\Controller;

use App\Entity\Produit;
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
        
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($produit);
            $pdo->flush();
        }

        return $this->render('produit/index.html.twig', [
            'produit' => $produit,
            'form_edit' => $form->createView(),
        ]);
    }
}
