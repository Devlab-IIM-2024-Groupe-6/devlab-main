<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use App\Form\DepotType;

class DepotController extends AbstractController
{
    #[Route('/depot', name: 'depot_form')]
    // #[IsGranted('ROLE_USER')] // Restriction : l'utilisateur doit être connecté
    public function depot(Request $request): Response
    {
        $form = $this->createForm(DepotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Logique pour traiter les données du formulaire
            $data = $form->getData();
            // Simule un enregistrement ou un traitement ici.
            $this->addFlash('success', 'Votre dépôt a été enregistré avec succès.');

            return $this->redirectToRoute('index'); // Redirection après succès
        }

        return $this->render('depot/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
