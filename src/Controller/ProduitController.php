<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use App\Form\ModifierProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SupprimerProduitType;


final class ProduitController extends AbstractController
{
    #[Route('/private-liste-produits', name: 'app_liste_produits')]
    public function listeProduits(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $em): Response
    {
        $produits = $produitRepository->findAll([], ['nom' => 'ASCd']);
        $form = $this->createForm(SupprimerProduitType::class, null, [
            'produits' => $produits
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedProduits = $form->get('produits')->getData();
            foreach ($selectedProduits as $produit) {
                $em->remove($produit);
            }
            $em->flush();
            $this->addFlash('notice', 'Produits supprimées avec succès');
            return $this->redirectToRoute('app_liste_produits');
        }
        return $this->render('produit/liste-produits.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),


        ]);
    }
    #[Route('/private-modifier-produit/{id}', name: 'app_modifier_produit')]

    public function modifierProduit(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierProduitType::class, $produit);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($produit);
                $em->flush();
                $this->addFlash('notice', 'Produit modifié');
                return $this->redirectToRoute('app_liste_produits');
            }
        }

        return $this->render('produit/modifier-produit.html.twig', [
            'form' => $form->createView()

        ]);
    }
    #[Route('/private-supprimer-produit/{id}', name: 'app_supprimer_produit')]
    public function supprimerProduit(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        if ($produit != null) {
            $em->remove($produit);
            $em->flush();
            $this->addFlash('notice', 'Produit supprimé');
        }
        return $this->redirectToRoute('app_liste_produits');
    }
    #[Route('/aimer/{id}', name: 'app_aimer_produit')]
    public function aimerProduit(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        $referer = $request->headers->get('referer');

        if ($produit) {
            $user = $this->getUser();

            if (!$user->getProduits()->contains($produit)) {
                $user->addProduit($produit);
            } else {
                $user->removeProduit($produit);
            }
            $em->persist($user);
            $em->flush();
        }
        return $this->redirect($referer ?? $this->generateUrl('app_accueil'));
    }
    #[Route('/favoris', name: 'app_favoris')]
    public function favoris(): Response
    {
        $user = $this->getUser();
        $produitsFavoris = $user->getProduits();
        return $this->render('produit/favoris.html.twig', [
            'produits' => $produitsFavoris,
        ]);
    }
    #[Route('/recherche', name: 'app_recherche')]
    public function rechercheProduit(Request $request, ProduitRepository $produitRepository): Response
    {
        $motCle = $request->query->get('q');

        $produits = $produitRepository->createQueryBuilder('p')
            ->where('p.nom LIKE :motCle')
            ->setParameter('motCle', '%' . $motCle . '%')
            ->getQuery()
            ->getResult();

        return $this->render('produit/recherche.html.twig', [
            'produits' => $produits,
            'motCle' => $motCle,
        ]);
    }
}
