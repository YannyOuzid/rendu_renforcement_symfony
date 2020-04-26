<?php

namespace App\Controller;

use App\Entity\Contenu;
use App\Entity\Panier;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
     * @Route("/")
     */


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        //Connexion a la base de données

        $em = $this->getDoctrine()->getManager();


        $users = $em->getRepository(Utilisateur::class)->findAll();

        $paniers = $em->getRepository(Panier::class)->findBy(
            array('etat' => false));

        $contenus = $em->getRepository(Contenu::class)->findBy(
            array('panier' => $paniers));

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'paniers' => $paniers,
            'contenus' => $contenus,
        ]);
    }
    

    /**
     * @Route("/editRole/{id}", name="editRole")
     */
    public function editRole(Utilisateur $user = null)
    {
        if($user == null){
            $this->addFlash('error', 'Utilisateur introuvable');
            return $this->redirectToRoute('accueil');

        }

        //Modification des roles des utilisateurs

        if($user->hasRole('ROLE_ADMIN') ){
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        }
        elseif($user->hasRole('ROLE_USER') ){
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        }
        elseif($user->hasRole('ROLE_SUPER_ADMIN') ){
            $user->setRoles(['ROLE_USER']);
        }
       
        

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Rôle modifié');
        return $this->redirectToRoute('admin');
    }


}
