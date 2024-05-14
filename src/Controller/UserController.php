<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateEmailFormType;
use App\Form\UpdatePasswordFieldType;
use App\Form\UpdateProfileFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
        /**
         * @var \App\Security\EmailVerifier
         */
        private EmailVerifier $emailVerifier;

        public function __construct(EmailVerifier $emailVerifier)
        {
                $this->emailVerifier = $emailVerifier;
        }

        #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/profile', name: 'user.profile')]
    public function profile(): Response
    {
            $user = $this->getUser();

            return $this->render('user/profile.html.twig', [
                'user' => $user,
            ]);
    }

        #[Route('/user/edit', name: 'user.send_email_verification')]
        public function sendEmailVerification(EntityManagerInterface $em): RedirectResponse
        {
                $user = $em->getRepository(User::class)->find($this->getUser()->getId());

                if($user->isVerified()) {
                        $this->addFlash('info', 'Votre adresse email est déjà vérifiée.');
                        return $this->redirectToRoute('user.profile');
                }

                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                        ->to($user->getEmail())
                        ->subject('Confirmer votre adresse email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

                $this->addFlash('success', 'Un email de vérification a été envoyé à votre adresse email.');
                return $this->redirectToRoute('user.profile');
        }

        #[Route('/user/update/email', name: 'user.update_email')]
        public function formForEmail(Request $request, EntityManagerInterface $em): Response
        {
                $form = $this->createForm(UpdateEmailFormType::class, $this->getUser());
                $form->handleRequest($request);

                if(!$this->getUser()) {
                        $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
                        return $this->redirectToRoute('app_login');
                }

                if($form->isSubmitted() && $form->isValid()) {
                        $em->flush();
                        $this->addFlash('success', 'Votre adresse email a été modifiée.');

                        if($this->getUser()->isVerified()) {
                                $this->getUser()->setIsVerified(false);
                                $em->flush();
                                return $this->redirectToRoute('user.send_email_verification');
                        }

                        // ensure we send a verification email if the user's email is not verified and they changed it
                        return $this->redirectToRoute('user.send_email_verification');
                }

                return $this->render('user/profile-email-update.html.twig', [
                        'form' => $form->createView(),
                        'user' => $this->getUser()
                ]);
        }

        #[Route('/user/update/password', name: 'user.update_password')]
        public function formForPassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
        {
                $form = $this->createForm(UpdatePasswordFieldType::class, $this->getUser());
                $form->handleRequest($request);

                if($this->getUser()) {
                        $user = $this->getUser();
                } else {
                        $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
                        return $this->redirectToRoute('app_login');
                }

                if($user && $form->isSubmitted() && $form->isValid()) {
                        // encode the plain password
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $form->get('password')->getData()
                            )
                        );
                        $em->flush();
                        $this->addFlash('success', 'Votre mot de passe a été modifié.');
                        return $this->redirectToRoute('user.profile');
                }

                return $this->render('user/profile-password-update.html.twig', [
                        'form' => $form->createView(),
                        'user' => $this->getUser()
                ]);
        }

        #[Route('/user/update/profile', name: 'user.update_profile')]
        public function formForProfile(): Response
        {
                $form = $this->createForm(UpdateProfileFormType::class, $this->getUser());

                return $this->render('user/profile-update.html.twig', [
                        'form' => $form->createView(),
                        'user' => $this->getUser()
                ]);
        }

        #[Route('/user/delete', name: 'user.delete')]
        public function deleteUser(EntityManagerInterface $em): RedirectResponse
        {
                $em->remove($this->getUser());
                $em->flush();

                $this->addFlash('success', 'Votre compte a été supprimé.');
                return $this->redirectToRoute('app_logout');
        }
}
