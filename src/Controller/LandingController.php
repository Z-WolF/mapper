<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'app_landing')]
    public function index(GameRepository $gr): Response
    {
        return $this->render('landing/index.html.twig', [
            'games' => $gr->findByEnabled(true),
        ]);
    }
}
