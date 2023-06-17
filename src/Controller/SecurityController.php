<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('@EasyAdmin/page/login.html.twig', [
            'page_title' => 'Mapper',
            'error' => $error,
            'last_username' => $lastUsername,
            'csrf_token_intention' => 'authenticate',
//            'forgot_password_enabled' => true,
//            'forgot_password_path' => $this->generateUrl('app_forgot_password_request'),
            'remember_me_enabled' => true,
        ]);
    }
}
