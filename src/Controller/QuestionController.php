<?php

namespace App\Controller;

use App\Entity\Categorie;
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

                $savedResponses = $reponses;
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
                    'name' => $quizz->getName(),
                    'reponses' => $savedResponses,
                ]);
        }
}
