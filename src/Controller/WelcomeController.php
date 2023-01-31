<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Repository\EgaFemaleContestantRepository;
use App\Repository\EgaMaleContestantRepository;
use App\Repository\MesseFemaleContestantRepository;
use App\Repository\MesseMaleContestantRepository;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    public function __construct(
        private readonly RegistrationRepository          $registrationRepository,
        private readonly EgaFemaleContestantRepository   $egaFemaleContestantRepository,
        private readonly EgaMaleContestantRepository     $egaMaleContestantRepository,
        private readonly MesseFemaleContestantRepository $messeFemaleContestantRepository,
        private readonly MesseMaleContestantRepository   $messeMaleContestantRepository,
    )
    {
    }

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
        $distinctCountries = $this->registrationRepository->findDistinctCountries();

        $registrationCount = count(
            array_filter(
                $this->registrationRepository->findAll(),
                fn(Registration $registration) => count($registration->getContestants()) > 0
            ));


        $categories['ega']['female']['total'] = $this->egaFemaleContestantRepository->count([]);
        $categories['ega']['male']['total'] = $this->egaMaleContestantRepository->count([]);
        $categories['messe']['female']['total'] = $this->messeFemaleContestantRepository->count([]);
        $categories['messe']['male']['total'] = $this->messeMaleContestantRepository->count([]);

        $categories['total'] =
            $categories['ega']['female']['total']
            + $categories['ega']['male']['total']
            + $categories['messe']['female']['total']
            + $categories['messe']['male']['total'];

        foreach (['-27', '-30', '-33', '-36', '-40', '-44', '-48', '-52', '-57', '+57'] as $weight) {
            $categories['ega']['female'][$weight] = $this->egaFemaleContestantRepository->count(['weightCategory' => $weight]);
        }
        foreach (['-28', '-31', '-34', '-37', '-43', '-46', '-50', '-55', '+55'] as $weight) {
            $categories['ega']['male'][$weight] = $this->egaMaleContestantRepository->count(['weightCategory' => $weight]);
        }
        foreach (['-36', '-40', '-44', '-48', '-52', '-57', '-63', '-70', '+70'] as $weight) {
            $categories['messe']['female'][$weight] = $this->messeFemaleContestantRepository->count(['weightCategory' => $weight]);
        }
        foreach (['-37', '-40', '-43', '-46', '-50', '-55', '-60', '-66', '-73', '+73'] as $weight) {
            $categories['messe']['male'][$weight] = $this->messeMaleContestantRepository->count(['weightCategory' => $weight]);
        }

        return $this->render('welcome/anonymous.html.twig', [
            'countries' => $distinctCountries,
            'clubsCount' => $registrationCount,
            'categories' => $categories
        ]);
    }
}
