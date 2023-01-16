<?php

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationEditFormType;
use App\Form\RegistrationEditPasswordFormType;
use App\Form\RegistrationFormType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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

    #[Route('/edit', name: 'registration_edit')]
    public function edit(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        /** @var Registration $registration */
        $registration = $this->getUser();

        $form = $this->createForm(RegistrationEditFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }

        return $this->render('registration/edit.html.twig', [
            'registration' => $registration,
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/edit/password', name: 'registration_edit_password')]
    public function changePassword(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface      $entityManager,
    ): Response
    {
        /** @var Registration $registration */
        $registration = $this->getUser();

        $form = $this->createForm(RegistrationEditPasswordFormType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $registration->setPassword(
                $userPasswordHasher->hashPassword(
                    $registration,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();
        }

        return $this->render('registration/edit_password.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'registration_delete', methods: 'POST')]
    public function deleteRegistration(Security $security, Registration $registration, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $registration) {
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        }

        $entityManager->remove($registration);
        $entityManager->flush();

        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('admin_index');
        }
        return $security->logout(false);
    }
}
