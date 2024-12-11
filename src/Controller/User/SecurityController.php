<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('app_logout'));
        }

        return $this->render('pages/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);

    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        return new RedirectResponse('/');
    }
}
