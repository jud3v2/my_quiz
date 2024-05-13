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
                }

                // la première question
                $firstQuestionId = $questions[0]->getId();

                // si la requête est de type POST, cela signifie que l'utilisateur a répondu à une question on incrémente la question suivante
                $nextQuestion = (int) $request->request->get('next_question') + 1;

                // TODO: faire en sorte de pouvoir stocker les réponses de l'utilisateur  dans la base de données si il est connecté
                if($request->isMethod('POST')) {
                        $session = $request->getSession();

                        $reponses = $session->get('reponses', []);
                        $reponses[] = [
                            'categorie' => $categorie->getId(),
                            'question' => $request->request->get('question'),
                            'user_reponse' => $request->request->get('reponse'),
                            'expected' => $this->expected($questionWithResponse, $nextQuestion)
                        ];
                        $session->set('reponses', $reponses);
                }

                return $this->render('categorie/index.html.twig', [
                    'questions' => $questionWithResponse,
                    'currentQuestion' => $nextQuestion ?? $firstQuestionId,   // récupère la question actuelle
                    'nextQuestion' => $nextQuestion ?? $firstQuestionId + 1,  // récupère la question suivant
                    'maxQuestionsId' => end($questionWithResponse)['question']->getId(),
                    'categorieId' => $categorie->getId(),
                    'name' => $categorie->getName(),
                ]);
        }

        private function expected($questionWithResponse, $nextQuestion)
        {
                foreach ($questionWithResponse[$nextQuestion - 1]['reponses'] as $reponse) {
                        if ($reponse->reponse_expected === true) {
                                return $reponse->reponse;
                        }
                }

                return false;
        }
}
