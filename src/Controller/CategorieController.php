<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Reponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
        #[Route('/quizz/{id}', name: 'quizz')]
        public function index(
            EntityManagerInterface $entityManager,
            Categorie              $categorie,
            Request                $request
        ): Response
        {
                // Les réponses sont un tableau vide pour pouvoir stocker les réponses des questions
                $reponses = [];

                // récupère toutes les questions de la catégorie (quizz)
                $questions = $entityManager->getRepository(Question::class)
                    ->findBy(['id_categorie' => $categorie->getId()]);

                // permet de récupérer chacune des réponses attendu pour chaque question
                foreach ($questions as $question) {
                        $reponses[$question->getId()] = $entityManager->getRepository(Reponse::class)
                            ->findBy(['id_question' => $question->getId()]);
                }

                // ici nous allons stocker les questions avec les réponses associées
                $questionWithResponse = [];

                foreach ($questions as $question) {
                        $questionWithResponse[$question->getId()] = [
                            'question' => $question,
                            'reponses' => $reponses[$question->getId()],
                        ];

                        // mélange les réponses pour chaque question afin d'évitée que les réponses soit toujours dans le même ordre
                        shuffle($questionWithResponse[$question->getId()]['reponses']);
                }

                // la première question
                $firstQuestionId = $questions[0]->getId();

                // si la requête est de type POST, cela signifie que l'utilisateur a répondu à une question on incrémente la question suivante
                $nextQuestion = (int) $request->request->get('next_question') + 1;

                // TODO: faire en sorte de pouvoir stocker les réponses de l'utilisateur  dans la base de données si il est connecté
                if($request->isMethod('POST')) {
                        // Si l'utilisateur n'a pas répondu à la question ont va lui afficher un message d'erreur
                        if($request->request->get('question') === null || $request->request->get('reponse') === null) {
                                $this->addFlash('error', 'Veuillez répondre à la question');
                                return $this->render('categorie/index.html.twig', [
                                    'questions' => $questionWithResponse,
                                    'currentQuestion' => $nextQuestion - 1 ?? $firstQuestionId,
                                    'nextQuestion' => $nextQuestion -1 ?? $firstQuestionId,
                                    'maxQuestionsId' => end($questionWithResponse)['question']->getId(),
                                    'categorieId' => $categorie->getId(),
                                    'name' => $categorie->getName(),
                                ]);
                        }
                        // récupère la session
                        $session = $request->getSession();

                        // récupère les données de l'utilisateur si jamais il n'est pas connecté
                        $reponses = $session->get('reponses', []);

                        // build de l'objet de réponse
                        $reponses[] = [
                            'categorie' => $categorie->getId(),
                            'question' => $request->request->get('question'),
                            'user_reponse' => $request->request->get('reponse'),
                            'expected' => $this->expected($questionWithResponse, $nextQuestion)
                        ];

                        // sauvegarde dans la session
                        $session->set('reponses', $reponses);

                        // on check si on est à la fin du quizz, si oui on redirect vers la page de résultat
                        if($nextQuestion > end($questionWithResponse)['question']->getId()) {
                            return $this->redirectToRoute('question.resultat', [
                                'id' => $categorie->getId(),
                                'reponses' => $reponses,
                                'questions' => $questionWithResponse,
                                'name' => $categorie->getName()
                            ]);
                        }
                }

                // permet de fix un bug.
                // Quand on commence un quizz et que la question n'est pas à un $nextQuestion est égal à
                // 1 au lieu de ne pas avoir de valeur
                // de ce fait si il est égal à 1 on le fix à la première question
                // même si la firstQuestion seras égal à 1 on le fix à 1
                if($nextQuestion === 1) {
                        $nextQuestion = $firstQuestionId;
                }

                // on retourne la vue avec toutes les données nécessaires.
                // sans afficher les réponses attendu dans le html
                return $this->render('categorie/index.html.twig', [
                    'questions' => $questionWithResponse,
                    'currentQuestion' => $nextQuestion ?? $firstQuestionId,   // récupère la question actuelle
                    'nextQuestion' => $nextQuestion ?? $firstQuestionId + 1,  // récupère la question suivant
                    'maxQuestionsId' => end($questionWithResponse)['question']->getId(),
                    'categorieId' => $categorie->getId(),
                    'name' => $categorie->getName(),
                ]);
        }


        /**
         * Permet de savoir qu'elle réponse est attendu pour la question demandé si inconnu retourne false dans tous les autres cas.
         * @param $questionWithResponse
         * @param $nextQuestion
         * @return string|false
         */
        private function expected($questionWithResponse, $nextQuestion): string|false
        {
                foreach ($questionWithResponse[$nextQuestion - 1]['reponses'] as $reponse) {
                        if ($reponse->reponse_expected === true) {
                                return $reponse->reponse;
                        }
                }

                return false;
        }
}
