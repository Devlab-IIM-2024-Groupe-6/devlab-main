<?php

// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route('/suivi', name: 'tracking')]
    public function tracking(): Response
    {
        return $this->render('tracking.html.twig', [
            'title' => 'Suivi de mon dépôt'
        ]);
    }

    #[Route('/wiki', name: 'wiki')]
    public function wiki(): Response
    {
        return $this->render('wiki.html.twig', [
            'title' => 'Wiki des problèmes récurrents'
        ]);
    }
}
