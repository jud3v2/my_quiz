<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
        public function __construct(private EmailVerifier $emailVerifier)
        {
        }

        #[Route('/register', name: 'app_register')]
        public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
        {
                $user = new User();
                $form = $this->createForm(RegistrationFormType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        // encode the plain password
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $form->get('plainPassword')->getData()
                            )
                        );

                        $entityManager->persist($user);
                        $entityManager->flush();

                        // generate a signed url and email it to the user
                        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                            (new TemplatedEmail())
                                ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                                ->to($user->getEmail())
                                ->subject('Confirmer votre adresse email')
                                ->htmlTemplate('email/confirmation_email.html.twig')
                        );

                        // do anything else you need here, like send an email

                        return $this->redirectToRoute('app_home');
                }

                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form,
                ]);
        }

        #[Route('/verify/email', name: 'app_verify_email')]
        public function verifyUserEmail(Request $request, TranslatorInterface $translator, EntityManagerInterface $em): Response
        {

                if (!$this->getUser()) {
                        $this->addFlash('error', 'Vous devez être connecté afin de procéder à la validation de votre adresse email.');
                        return $this->redirectToRoute('app_login');
                }
                // validate email confirmation link, sets User::isVerified=true and persists
                try {
                        $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
                } catch (VerifyEmailExceptionInterface $exception) {
                        $this->addFlash('error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

                        return $this->redirectToRoute('app_login');
                }
                $user = $em->getRepository(User::class)
                    ->find($this->getUser()->getId());
                $user->setIsVerified(true);
                $em->flush();

                $this->addFlash('success', 'Votre adresse email à bien été vérifié.');
                return $this->redirectToRoute('app_home');
        }
}
