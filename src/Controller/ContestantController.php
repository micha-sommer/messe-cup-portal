<?php

namespace App\Controller;

use App\Entity\EgaFemaleContestant;
use App\Entity\EgaMaleContestant;
use App\Entity\MesseFemaleContestant;
use App\Entity\MesseMaleContestant;
use App\Entity\Registration;
use App\Form\EgaFemaleContestantsListType;
use App\Form\EgaMaleContestantsListType;
use App\Form\MesseFemaleContestantsListType;
use App\Form\MesseMaleContestantsListType;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
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
        return $this->edit($request, $entityManager, EgaMaleContestantsListType::class, 'ega', 'male');
    }

    #[Route('/ega/female/edit', name: 'contestants_edit_ega_female')]
    public function editEgaFemale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit($request, $entityManager, EgaFemaleContestantsListType::class, 'ega', 'female');
    }

    #[Route('/messe/male/edit', name: 'contestants_edit_messe_male')]
    public function editMesseMale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit($request, $entityManager, MesseMaleContestantsListType::class, 'messe', 'male');
    }

    #[Route('/messe/female/edit', name: 'contestants_edit_messe_female')]
    public function editMesseFemale(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit($request, $entityManager, MesseFemaleContestantsListType::class, 'messe', 'female');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    private function edit(
        Request                $request,
        EntityManagerInterface $entityManager,
        string                 $type,
        string                 $cup,
        string                 $gender,
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
            'cup' => $cup,
            'gender' => $gender,
        ]);
    }

    #[Route('/{id}/move/from/{fromCup}/{fromGender}/to/{toCup}/{toGender}', name: 'contestants_move')]
    public function move(
        int                    $id,
        string                 $fromCup,
        string                 $fromGender,
        string                 $toCup,
        string                 $toGender,
        EntityManagerInterface $entityManager,
    ): Response
    {
        /** @var Registration $registration */
        $registration = $this->getUser();

        $fromCollection = $this->getRepo($registration, $fromCup, $fromGender);
        $toCollection = $this->getRepo($registration, $toCup, $toGender);

        $oldElement = $fromCollection->findFirst(fn($key, $value) => $value->getId() === $id);
        $fromCollection->removeElement($oldElement);

        $newElement = $this->createEntity($toCup, $toGender);
        $this->copyData($oldElement, $newElement, $toGender);
        $toCollection->add($newElement);

        $entityManager->flush();

        return $this->redirectToRoute('contestants_edit_'.$toCup.'_'.$toGender);
    }

    private function getRepo(Registration $registration, string $cup, string $gender): Collection
    {
        if ($cup == 'messe') {
            if ($gender == 'male') {
                return $registration->getMesseMaleContestants();
            } elseif ($gender == 'female') {
                return $registration->getMesseFemaleContestants();
            } else {
                throw new RuntimeException();
            }
        } elseif ($cup == 'ega') {
            if ($gender == 'male') {
                return $registration->getEgaMaleContestants();
            } elseif ($gender == 'female') {
                return $registration->getEgaFemaleContestants();
            } else {
                throw new RuntimeException();
            }
        } else {
            throw new RuntimeException();
        }
    }

    private function createEntity(string $cup, string $gender): object
    {
        if ($cup == 'messe') {
            if ($gender == 'male') {
                return new MesseMaleContestant();
            } elseif ($gender == 'female') {
                return new MesseFemaleContestant();
            } else {
                throw new RuntimeException();
            }
        } elseif ($cup == 'ega') {
            if ($gender == 'male') {
                return new EgaMaleContestant();
            } elseif ($gender == 'female') {
                return new EgaFemaleContestant();
            } else {
                throw new RuntimeException();
            }
        } else {
            throw new RuntimeException();
        }
    }

    private function copyData(object $fromEntity, object $toEntity, string $gender)
    {
        $toEntity->setRegistration($fromEntity->getRegistration());
        $toEntity->setFirstName($fromEntity->getFirstName());
        $toEntity->setLastName($fromEntity->getLastName());
        $toEntity->setYear(0);
        $toEntity->setGender($gender);
        $toEntity->setWeightCategory($fromEntity->getWeightCategory());
        $toEntity->setComment($fromEntity->getComment());
        $toEntity->setCreatedAt($fromEntity->getCreatedAt());
    }
}
