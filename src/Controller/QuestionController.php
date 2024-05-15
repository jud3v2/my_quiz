<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\UserHistory;
use App\Entity\UserReponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuestionController extends AbstractController
{
        #[Route('/resultat', name: 'question.resultat')]
        public function showResultat(Request $request, EntityManagerInterface $entityManager): Response
        {
                $session = $request->getSession();
                $reponses = $session->get('reponses', []);

                // check if we have a reponse session if not we will redirect to home with a info message to tell user the reponses are stored in history
                // because the session reponse has been cleared
                if (!$reponses) {
                        $this->addFlash('info', "Veuillez vérifier votre historique les réponses y ont été stockées.");
                        return $this->redirectToRoute('app_home');
                }

                $quizzId = $reponses[0]['categorie'];

                $score = 0;
                $total = 0;

                // calculate the score
                foreach ($reponses as $reponse) {
                        $quizz = $entityManager->getRepository(Categorie::class)
                            ->find($reponse['categorie']);

                        if ($reponse['user_reponse'] === $reponse['expected']) {
                                $score++;
                        }

                        $total++;
                }

                if ($user = $this->getUser()) {
                        $quizz = $entityManager->getRepository(Categorie::class)
                            ->find($quizzId);
                        // clear session reponses
                        $session->set('reponses', []);
                        return $this->createUserHistories($user, $quizz, $reponses, $score, $total, $entityManager);
                } else {
                        // get previous history of quizz for no connected users
                        $history = $session->get('history', []);

                        $history[] = [
                            'reponses' => $reponses,
                            'score' => $score,
                            'total' => $total,
                            'date' => new \DateTime(),
                        ];

                        // save the history and reset the reponses for the next quizz
                        $session->set('history', $history);
                        $session->set('reponses', []);

                        return $this->render('question/resultat.html.twig', [
                            'score' => $score,
                            'total' => $total,
                            'quizzId' => $quizzId,
                            'name' => trim($quizz->getName()),
                            'reponses' => $reponses,
                        ]);
                }
        }

        private function createUserHistories(User $user, Categorie $quizz, array $reponses, int $score, int $total, EntityManagerInterface $em)
        {
                $_ = [
                    'score' => $score,
                    'total' => $total,
                    'date' => new \DateTimeImmutable(),
                    'quizz' => $quizz,
                    'user' => $user,
                    'reponses' => $reponses,
                ];

                $history = (new UserHistory())->fill($_);

                $em->persist($history);
                $em->flush();

                foreach ($_['reponses'] as $reponse) {
                        $_reponse = [
                            'user' => $user,
                            'history' => $history,
                            'question' => $em->getRepository(Question::class)
                                ->find($reponse['question_id']),
                            'answer' => $reponse['user_reponse'],
                            'expected' => $reponse['expected'],
                        ];

                        $reponseToBePersisted = (new UserReponse())->fill($_reponse);
                        $em->persist($reponseToBePersisted);
                        $em->flush();
                }

                return $this->render('question/resultat.html.twig', [
                    'score' => $score,
                    'total' => $total,
                    'quizzId' => $quizz->getId(),
                    'name' => trim($quizz->getName()),
                    'reponses' => $reponses,
                ]);
        }
}
