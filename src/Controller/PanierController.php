<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="index")
     */
    public function index()
    {

        $panierRepository = $this->getDoctrine()
        ->getRepository(Panier::class)
        ->findAll();

        $quantiteTotal = 0;
        $montantTotal = 0;

        foreach($panierRepository as $panier){
            $quantiteTotal += $panier->getQuantite();
            $montantTotal += $panier->getProduit()->getPrix()*$panier->getQuantite();
        }

        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository,
            'quantite' => $quantiteTotal,
            'montant' => $montantTotal,
        ]);
    }

    /**
     * @Route("/removePanier/{id}", name="removePanier")
     */
    public function removeProduit($id,Request $request, EntityManagerInterface $entityManager){
        $panier = $this->getDoctrine()->getRepository(Panier::class)->find($id);

        $entityManager->remove($panier);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
