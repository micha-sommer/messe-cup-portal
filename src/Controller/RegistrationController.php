<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationFormType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/registration')]
class RegistrationController extends AbstractController
{
    #[Route('/new', name: 'registration_new')]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $registration = new Registration();
        $form = $this->createForm(RegistrationFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $registration->setPassword(
                $userPasswordHasher->hashPassword(
                    $registration,
                    $form->get('plainPassword')->getData()
                )
            );

            $registration->setTimestamp(DateTimeImmutable::createFromMutable(new DateTime()));
            $entityManager->persist($registration);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('welcome');
        }

        return $this->render('registration/new.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
