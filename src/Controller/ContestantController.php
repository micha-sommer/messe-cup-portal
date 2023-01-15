<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\EgaFemaleContestantsListType;
use App\Form\EgaMaleContestantsListType;
use App\Form\MesseFemaleContestantsListType;
use App\Form\MesseMaleContestantsListType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/contestants')]
class ContestantController extends AbstractController
{
    #[Route('/ega/male/edit', name: 'contestants_edit_ega_male')]
    public function editEgaMale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(
            $request,
            $entityManager,
            EgaMaleContestantsListType::class,
            'contestant.title.ega',
            'contestant.data.title.male',
        );
    }

    #[Route('/ega/female/edit', name: 'contestants_edit_ega_female')]
    public function editEgaFemale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(
            $request,
            $entityManager,
            EgaFemaleContestantsListType::class,
            'contestant.title.ega',
            'contestant.data.title.female',
        );
    }

    #[Route('/messe/male/edit', name: 'contestants_edit_messe_male')]
    public function editMesseMale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(
            $request,
            $entityManager,
            MesseMaleContestantsListType::class,
            'contestant.title.messe',
            'contestant.data.title.male',
        );
    }

    #[Route('/messe/female/edit', name: 'contestants_edit_messe_female')]
    public function editMesseFemale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(
            $request,
            $entityManager,
            MesseFemaleContestantsListType::class,
            'contestant.title.messe',
            'contestant.data.title.female',
        );
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(
        Request                $request,
        EntityManagerInterface $entityManager,
        string                 $type,
        string                 $title,
        string                 $contestantTitle,
    ): Response
    {
        /** @var Registration $registration */
        $registration = $this->getUser();

        $form = $this->createForm(
            $type,
            $registration,
            ['year' => $this->getParameter('app.year')],
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('contestant/edit.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'contestantTitle' => $contestantTitle,
        ]);
    }
}
