<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\Panier;
use App\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produits")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $produit = $form->getData();
            $image = $produit->getPhoto();

            $imageName = md5(uniqid()).'.'.$image->guessExtension();

            $image->move($this->getParameter('upload_files'), $imageName);
            $produit->setPhoto($imageName);

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produits');
        }

        $produitRepository = $this->getDoctrine()
        ->getRepository(Produit::class)
        ->findAll();

        return $this->render('produit/index.html.twig', [
            'produitForm' => $form->createView(),
            'produits' => $produitRepository,

        ]);
    }

    /**
     * @Route("/produit/{id}", name="produit")
     */
    public function produit($id,Request $request, EntityManagerInterface $entityManager ){

        $panier = new Panier();

        $produit = $this->getDoctrine()
        ->getRepository(Produit::class)
        ->find($id);

        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $panier = $form->getData();
            $panier->setaddAt(new \DateTime());
            $panier->setProduit($produit);
            $panier->setEtat(false);

            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }



        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
            'panierForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function removeProduit($id,Request $request, EntityManagerInterface $entityManager){
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $produit->deleteFile();

        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('produits');
    }
}
