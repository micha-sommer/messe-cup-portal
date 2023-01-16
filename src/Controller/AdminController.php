<?php

namespace App\Controller;

use App\Repository\EgaFemaleContestantRepository;
use App\Repository\EgaMaleContestantRepository;
use App\Repository\MesseFemaleContestantRepository;
use App\Repository\MesseMaleContestantRepository;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_index')]
    public function index(RegistrationRepository $registrationRepository): Response
    {
        $registrations = $registrationRepository->findAll();

        $egaCount = 0;
        $egaMaleCount = 0;
        $egaFemaleCount = 0;
        $messeCount = 0;
        $messeFemaleCount = 0;
        $messeMaleCount = 0;
        foreach ($registrations as $registration) {
            $egaMaleContestants = $registration->getEgaMaleContestants();
            $egaCount += $egaMaleContestants->count();
            $egaMaleCount += $egaMaleContestants->count();

            $egaFemaleContestants = $registration->getEgaFemaleContestants();
            $egaCount += $egaFemaleContestants->count();
            $egaFemaleCount += $egaFemaleContestants->count();

            $messeMaleContestants = $registration->getMesseMaleContestants();
            $messeCount += $messeMaleContestants->count();
            $messeMaleCount += $messeMaleContestants->count();

            $messeFemaleContestants = $registration->getMesseFemaleContestants();
            $messeCount += $messeFemaleContestants->count();
            $messeFemaleCount += $messeFemaleContestants->count();
        }

        return $this->render('admin/index.html.twig', [
            'registrations' => $registrations,
            'registrationsCount' => $registrationRepository->count([]),
            'egaCount' => $egaCount,
            'egaMaleCount' => $egaMaleCount,
            'egaFemaleCount' => $egaFemaleCount,
            'messeCount' => $messeCount,
            'messeFemaleCount' => $messeFemaleCount,
            'messeMaleCount' => $messeMaleCount,
        ]);
    }

    #[Route('/ega', name: 'admin_ega')]
    public function egaOverview(
        EgaFemaleContestantRepository $egaFemaleContestantRepository,
        EgaMaleContestantRepository $egaMaleContestantRepository,
    ): Response
    {
        return $this->render('admin/contestants.html.twig', [
            'femaleContestants' => $egaFemaleContestantRepository->findAll(),
            'maleContestants' => $egaMaleContestantRepository->findAll(),
            'title' => 'admin.ega.title',
            'ageCategory' => 'ega',
        ]);
    }

    #[Route('/messe', name: 'admin_messe')]
    public function messeOverview(
        MesseFemaleContestantRepository $messeFemaleContestantRepository,
        MesseMaleContestantRepository $messeMaleContestantRepository,
    ): Response
    {
        return $this->render('admin/contestants.html.twig', [
            'femaleContestants' => $messeFemaleContestantRepository->findAll(),
            'maleContestants' => $messeMaleContestantRepository->findAll(),
            'title' => 'admin.messe.title',
            'ageCategory' => 'messe',
        ]);
    }

}
