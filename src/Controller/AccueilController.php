<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();

        
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $fichier = $form->get('photoUpload')->getData();

            if($fichier){
                $nomFichier = uniqid() . '.' . $fichier->guessExtension();

                try{
                    //On essaie de deplacer le fichier
                    $fichier->move(
                    $this->getParameter('upload_dir'),
                    $nomFichier
                    );
                }
                catch(FileException $e){
                    $this->addFlash('danger', "Impossible d'uploder le fichier");
                    return $this->redirecttoRoute('home');

                }

                $produit->setPhoto($nomFichier);
            }
            $pdo->persist($produit);
            $pdo->flush();
        }

        $produits = $pdo->getRepository(Produit::class)->findAll();
        return $this->render('accueil/index.html.twig', [
            'produits' => $produits,
            'form_ajout' => $form->createView(),
        ]);
    }
}
