<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailController extends AbstractController
{

        private MessageBusInterface $bus;

        public function __construct(MessageBusInterface $bus)
        {
                $this->bus = $bus;
        }

        #[Route('/send-email/to/{id}/{uuid}/user/has/done/quizz', name: 'admin.emailing.send.email.to.user.has.done.quizz')]
        public function userHasDoneAQuizz(Categorie $quizz, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
        {

                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                $uuid = $request->get('uuid');
                $user = $em->getRepository(User::class)->findOneBy(['uuid' => $uuid]);

                if(!$user) {
                        $this->addFlash('danger', "L'utilisateur n'existe pas.");
                        return $this->redirectToRoute('admin.emailing');
                }

                $email = (new TemplatedEmail())
                    ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                    ->to($user->getEmail())
                    ->subject('Vous avez terminer un quizz en particulier')
                    ->htmlTemplate('email/user_has_done_a_quizz.html.twig')
                    ->context([
                        'user' => $user,
                        'quizz' => $quizz,
                            'uri' => $this->generateUrl('history', [], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]);

                $this->bus->dispatch(new SendEmailMessage($email));

                $this->addFlash('info', "L'email a bien été envoyé à l'utilisateur {$user->getEmail()}.");
                return $this->redirectToRoute('admin.emailing');
        }

        #[Route('/send-email/to/{uuid}/{id}/user/has/not/done/quizz', name: 'admin.emailing.send.email.to.user.has.not.done.quizz')]
        public function useHasNotDoneQuizz(Categorie $quizz, Request $request, EntityManagerInterface $em, MailerInterface $mailer): RedirectResponse
        {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                $uuid = $request->get('uuid');
                $user = $em->getRepository(User::class)->findOneBy(['uuid' => $uuid]);

                if(!$user) {
                    $this->addFlash('danger', "L'utilisateur n'existe pas.");
                    return $this->redirectToRoute('admin.emailing');
                }

                $email = (new TemplatedEmail())
                    ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                    ->to($user->getEmail())
                    ->subject('Vous n\'avez pas effectué un quizz en particulier')
                    ->htmlTemplate('email/user_has_not_done_a_quizz.html.twig')
                    ->context([
                        'user' => $user,
                        'quizz' => $quizz,
                            'uri' => $this->generateUrl('quizz', ['id' => $quizz->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]);

                $mailer->send($email);

                $this->addFlash('info', "L'email a bien été envoyé à l'utilisateur {$user->getEmail()}.");

                return $this->redirectToRoute('admin.emailing');
        }

        #[Route('/send-email/to/{uuid}/user/connected/since/one/month', name: 'admin.emailing.send.email.to.user.connected.since.one.month')]
        public function userConnectedSinceOneMonth(User $user, MailerInterface $mailer): RedirectResponse
        {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                $email = (new TemplatedEmail())
                    ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                    ->to($user->getEmail())
                    ->subject('Vous êtes connecté depuis un mois')
                    ->htmlTemplate('email/user_connected_since_one_month.html.twig')
                    ->context([
                        'user' => $user,
                            'uri' => $this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]);

                $mailer->send($email);

                $this->addFlash('info', "L'email a bien été envoyé à l'utilisateur {$user->getEmail()}.");

                return $this->redirectToRoute('admin.emailing');
        }

        #[Route('/send-email/to/{uuid}/user/not/connected/since/one/month', name: 'admin.emailing.send.email.to.user.not.connected.since.one.month')]
        public function userNotConnectedSinceOneMonth(User $user, MailerInterface $mailer): RedirectResponse
        {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                $email = (new TemplatedEmail())
                    ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                    ->to($user->getEmail())
                    ->subject('Vous ne vous êtes pas connecté depuis un mois')
                    ->htmlTemplate('email/user_not_connected_since_one_month.html.twig')
                    ->context([
                        'user' => $user,
                            'uri' => $this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]);

                $mailer->send($email);
                
                $this->addFlash('info', "L'email a bien été envoyé à l'utilisateur {$user->getEmail()}.");

                return $this->redirectToRoute('admin.emailing');
        }
}
