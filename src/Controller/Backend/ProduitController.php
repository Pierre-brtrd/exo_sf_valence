<?php

namespace App\Controller\Backend;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/admin/produit')]
class ProduitController extends AbstractController
{
    public function __construct(
        private ProduitRepository $repoProduit
    ) {
    }

    #[Route('', name: 'admin.produit.index')]
    public function index(): Response
    {
        $produits = $this->repoProduit->findAll();

        return $this->render('Backend/Produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/new', name: 'admin.produit.create')]
    public function createProduct(Request $request): Response|RedirectResponse
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repoProduit->add($produit, true);

            return $this->redirectToRoute('admin.produit.index');
        }

        return $this->renderForm('Backend/Produit/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: "admin.produit.edit")]
    public function editProduct(?Produit $produit, Request $request): Response|RedirectResponse
    {
        if (!$produit instanceof Produit) {
            return $this->redirectToRoute('admin.produit.index');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repoProduit->add($produit, true);

            return $this->redirectToRoute('admin.produit.index');
        }

        return $this->renderForm('Backend/Produit/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/delete', name: 'admin.produit.delete', methods: ['POST', 'DELETE'])]
    public function deleteProduit(?Produit $produit, Request $request): RedirectResponse
    {
        if (!$produit instanceof Produit) {
            return $this->redirectToRoute('admin.produit.index');
        }

        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->get('_token'))) {
            $this->repoProduit->remove($produit, true);

            return $this->redirectToRoute('admin.produit.index');
        }

        $this->addFlash('error', 'Token invalide');

        return $this->redirectToRoute('admin.produit.index');
    }
}
