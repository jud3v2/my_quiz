<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
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
    public function profile()
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
}
