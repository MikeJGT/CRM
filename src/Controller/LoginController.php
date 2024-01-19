<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //get Errors on login.
        $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'errors' => $error,
            'last_username'=>$lastUsername,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(){
        return $this->redirectToRoute('login');
    }
}
