<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\User;
use App\Entity\UserHistory;
use App\Form\AdminCreateUserFormType;
use App\Form\AdminUserFormType;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{

        /**
         * @var \App\Security\EmailVerifier
         */
        private EmailVerifier $emailVerifier;

        public function __construct(EmailVerifier $emailVerifier)
        {
                $this->emailVerifier = $emailVerifier;
        }

        #[Route('/admin', name: 'admin.index')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminDashboard(): Response
        {
                return $this->render('admin/index.html.twig');
        }

        #[Route('/admin/users', name: 'admin.users')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminGestionUtilisateur(EntityManagerInterface $em): Response
        {
                $users = $em->getRepository(User::class)->findAll();

                return $this->render('admin/admin-users.html.twig', [
                    'users' => $users
                ]);
        }

        #[Route('/admin/users/create', name: 'admin.users.create')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminCreateUser(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $userPasswordHasher): RedirectResponse|Response
        {
                $form = $this->createForm(AdminCreateUserFormType::class, new User());
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                        $user = $form->getData();
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                                $user,
                                $form->get('password')->getData()
                            )
                        );
                        $em->persist($user);
                        $em->flush();

                        $this->addFlash('success', "L'utilisateur: {$user->getUsername()} a bien été créé.");
                        return $this->redirectToRoute('admin.users');
                }

                return $this->render('admin/admin-create-user.html.twig', [
                    'form' => $form->createView()
                ]);
        }

        #[Route('/admin/users/{uuid}', name: 'admin.users.details')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminShowDetailsOfUsers(User $user, Request $request, EntityManagerInterface $em): Response
        {
                $form = $this->createForm(AdminUserFormType::class, $user);
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    $em->flush();

                    $this->addFlash('success', "L'utilisateur: {$user->getUsername()} a bien été modifié.");
                    return $this->redirectToRoute('admin.users');
                }

                return $this->render('admin/admin-user-details.html.twig', [
                    'user' => $user,
                    'form' => $form->createView()
                ]);
        }

        #[Route('/admin/users/{uuid}/switch/role', name: 'admin.users.switch.role')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminSwitchUserToAdminOrUserRole(User $user, EntityManagerInterface $em): RedirectResponse
        {
                $roles = $user->getRoles();

                if(in_array('ROLE_ADMIN', $roles)) {
                        // Remove the role
                        foreach ($roles as $key => $role) {
                                if($role === 'ROLE_ADMIN') {
                                        unset($roles[$key]);
                                }
                        }
                } else {
                        // add the role to the array
                        $roles[] = 'ROLE_ADMIN';
                }

                $user->setRoles($roles);
                $em->flush();

                $this->addFlash('success', "Le role de l'utilisateur: {$user->getUsername()} a bien été modifié.");
                return new RedirectResponse($this->generateUrl('admin.users'));
        }

        #[Route('/admin/users/{uuid}/delete', name: 'admin.users.delete')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function deleteUser(User $user, EntityManagerInterface $em): RedirectResponse
        {
                // get everything related to user and delete it
                $quizz = $em->getRepository(Categorie::class)->findBy(['user' => $user]);

                foreach ($quizz as $q) {
                        $question = $em->getRepository(Question::class)->findBy(['id_categorie' => $q->getId()]);
                        foreach ($question as $quest) {
                                $reponse = $em->getRepository(Reponse::class)->findBy(['id_question' => $quest->getId()]);
                                foreach ($reponse as $rep) {
                                        $em->remove($rep);
                                }
                                $em->remove($quest);
                        }
                        $em->remove($q);
                }

                $em->remove($user);
                $em->flush();

                $this->addFlash('success', "L'utilisateur: {$user->getUsername()} a bien été supprimé.");
                return new RedirectResponse($this->generateUrl('admin.users'));
        }

        #[Route('/admin/users/{uuid}/delete/confirm', name: 'admin.users.delete.page')]
        public function deleteUserPage(User $user): Response
        {
                return $this->render('admin/admin-user-delete.html.twig', [
                    'user' => $user
                ]);
        }

        #[Route('/admin/quizz-categorie', name: 'admin.quizz.categorie')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminGestionDesQuizzEtCategorie(EntityManagerInterface $em): Response
        {
                $quizz = $em->getRepository(Categorie::class)->findAll();

                return $this->render('admin/admin-quizz-categorie.html.twig', [
                    'quizz' => $quizz
                ]);
        }

        #[Route('/update/quizz/{id}', name: 'admin.quizz.categorie.edit')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: "Vous n'avez pas les autorisation nécessaires pour cette action",
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminGestionDuQuizz(Categorie $categorie, EntityManagerInterface $em, Request $request): Response|RedirectResponse
        {
                $reponses = [];
                $question = $em->getRepository(Question::class)->findBy(['id_categorie' => $categorie->getId()]);

                foreach ($question as $q) {
                    $reponses[] = $em->getRepository(Reponse::class)->findBy(['id_question' => $q->getId()]);
                }

                return $this->render('admin/admin-quizz-edit.html.twig', [
                    'quizz' => $categorie,
                        'question' => $question,
                        'reponse' => $reponses
                ]);
        }

        #[Route('/admin/quizz/{id}/delete', name: 'admin.quizz.delete.page')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: "Vous n'avez pas les autorisation nécessaires pour cette action",
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminDeleteQuizzPage(Categorie $categorie): Response
        {
                return $this->render('admin/admin-quizz-delete.html.twig', [
                    'quizz' => $categorie
                ]);
        }

        #[Route('/admin/quizz/{id}/delete/confirmed', name: 'admin.quizz.delete')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: "Vous n'avez pas les autorisation nécessaires pour cette action",
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminDeleteQuizz(Categorie $categorie, EntityManagerInterface $em): RedirectResponse
        {
                $savedName = $categorie->getName();
                $userHistory = $em->getRepository(UserHistory::class)->findBy(['quizz' => $categorie->getId()]);
                $question = $em->getRepository(Question::class)->findBy(['id_categorie' => $categorie->getId()]);
                $reponse = $em->getRepository(Reponse::class)->findBy(['id_question' => $question]);

                foreach ($reponse as $r) {
                        $em->remove($r);
                }

                foreach ($question as $q) {
                    $em->remove($q);
                }

                foreach ($userHistory as $uh) {
                        $em->remove($uh);
                }

                $em->remove($categorie);
                $em->flush();

                $this->addFlash('success', "Le quizz: {$savedName} a bien été supprimé.");
                return new RedirectResponse($this->generateUrl('admin.quizz.categorie'));
        }

        #[Route('/admin/email', name: 'admin.emailing')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminGestionDesEmails(EntityManagerInterface $em): Response
        {
                $users = $em->getRepository(User::class)->findAll();

                return $this->render('admin/admin-email.html.twig', [
                    'users' => $users
                ]);
        }

        #[Route('/admin/email/send/to/{uuid}', name: 'admin.users.email')]
        public function prepareAdminToChoiceEmailToSendToUsers(User $user, Request $request, EntityManagerInterface $em): Response
        {
                if(!$user->isVerified()) {
                        // send email verification to the user
                        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                            (new TemplatedEmail())
                                ->from(new Address("postmaster@sandboxbb95dbd1520f466484c55ad4e119f22c.mailgun.org", 'My Quizz'))
                                ->to($user->getEmail())
                                ->subject('Confirmer votre adresse email')
                                ->htmlTemplate('email/confirmation_email.html.twig')
                        );

                        // send error to the administrator and telle him the email vérification was send.
                        $this->addFlash('error', 'Cet utilisateur n\'a pas encore vérifié son email. Un email de vérification vient de lui être envoyé.');
                        return $this->redirectToRoute('admin.emailing');
                }

                $quizz = $em->getRepository(Categorie::class)->findAll();

                $quizzDone = [];
                $quizzNotDone = [];

                foreach ($quizz as $quiz) {
                        $tmp = $em->getRepository(UserHistory::class)->findBy(['quizz' => $quiz->getId()]);

                        if($tmp) {
                                $quizzDone[] = $quiz;
                        } else {
                                $quizzNotDone[] = $quiz;
                        }
                }

                return $this->render('admin/admin-email-send.html.twig', [
                    'user' => $user,
                        'quizzDone' => $quizzDone,
                        'quizzNotDone' => $quizzNotDone
                ]);
        }

        #[Route('/admin/email/send/to/{uuid}/user-has-not-done-quizz', name: 'admin.emailing.send.not.done.quizz')]
        public function postUserHasNotDoneAQuizz(User $user, Request $request, EntityManagerInterface $em): RedirectResponse
        {
                $quizzId = $request->request->get('quizz');
                $quizz = $em->getRepository(Categorie::class)->find($quizzId);

                return $this->redirectToRoute('admin.emailing.send.email.to.user.has.not.done.quizz', [
                    'uuid' => $user->getUuid(),
                    'id' => $quizz->getId(),
                        'user' => $user
                ]);
        }

        #[Route('/admin/email/send/to/{uuid}/user-has-done-quizz', name: 'admin.emailing.send.done.quizz')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function postUserHasDoneAQuizz(User $user, Request $request, EntityManagerInterface $em): RedirectResponse
        {
                $quizzId = $request->request->get('quizz');
                $quizz = $em->getRepository(Categorie::class)->find($quizzId);

                return  $this->redirectToRoute('admin.emailing.send.email.to.user.has.done.quizz', [
                    'uuid' => $user->getUuid(),
                    'id' => $quizz->getId(),
                    'user' => $user
                ]);
        }

        #[Route('/admin/stats', name: 'admin.stats')]
        #[IsGranted(
            attribute: 'ROLE_ADMIN',
            message: 'Les administrateurs de ce site sont les seuls autorisé à accéder au dashboard',
            statusCode: 403,
            exceptionCode: 403
        )]
        public function adminStatistique(): Response
        {
                return $this->render('admin/admin-stats.html.twig');
        }
}
