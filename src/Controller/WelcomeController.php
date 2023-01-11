<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    #[Route('/')]
    public function indexNoLocale(): Response
    {
        return $this->redirectToRoute('welcome', ['_locale' => 'de']);
    }

    #[Route('/{_locale<%app.supported_locales%>}')]
    public function index(): Response
    {
        return $this->redirectToRoute('welcome');
    }

    #[Route('/{_locale<%app.supported_locales%>}/welcome', name: 'welcome')]
    public function welcome(): Response
    {
        if ($this->getParameter('app.is_active')) {
            return $this->activeWelcome();

        } else {
            return $this->render('welcome/waiting.html.twig');
        }
    }

    public function activeWelcome(): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->activeAuthenticatedWelcome();
        } else {
            return $this->activeAnonymousWelcome();
        }
    }

    public function activeAuthenticatedWelcome(): Response
    {
        return $this->render('welcome/index.html.twig', [
            'registration' => $this->getUser(),
        ]);
    }

    public function activeAnonymousWelcome(): Response
    {
        return $this->render('welcome/anonymous.html.twig');
    }
}
