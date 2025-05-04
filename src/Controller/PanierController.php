<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Entity\Panier;
use App\Entity\Ajouter;
use Doctrine\ORM\EntityManagerInterface;

final class PanierController extends AbstractController
{
    #[Route('/private-ajout-panier/{id}', name: 'app_ajout_panier')]
    public function ajout_panier(Produit $produit, EntityManagerInterface $em): Response
    {
        if ($produit) {
            if ($this->getUser()->getPanier()) {
                $panier = $this->getUser()->getPanier();
            } else {
                $panier = new Panier();
            }
            $trouver = false;
            foreach ($panier->getAjouters() as $ajouter) {
                if ($ajouter->getProduit() == $produit) {
                    $trouver = true;
                    $ajouter->setQte($ajouter->getQte() + 1);
                    $em->persist($ajouter);
                }
            }
            if (!$trouver) {
                $ajouter = new Ajouter();
                $ajouter->setPanier($panier);
                $ajouter->setProduit($produit);
                $ajouter->setQte(1);
                $em->persist($ajouter);
            }
            $this->getUser()->setPanier($panier);
            $em->persist($panier);
            $em->persist($this->getUser());
            $em->flush();
        }
        return $this->redirectToRoute('app_accueil');
    }
    #[Route('/private-panier', name: 'app_panier')]
    public function panier(): Response
    {
       return $this->render('panier/panier.html.twig');
    }
}
