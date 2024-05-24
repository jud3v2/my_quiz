<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Reponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        $nextQuestion = (int)$request->request->get('next_question') + 1;

        if ($request->isMethod('POST')) {
            // Si l'utilisateur n'a pas répondu à la question, on va lui afficher un message d'erreur
            if ($request->request->get('question') === null || $request->request->get('reponse') === null) {
                $this->addFlash('error', 'Veuillez répondre à la question');
                return $this->render('categorie/index.html.twig', [
                    'questions' => $questionWithResponse,
                    'currentQuestion' => $nextQuestion - 1 ?? $firstQuestionId,
                    'nextQuestion' => $nextQuestion - 1 ?? $firstQuestionId,
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
                'question_id' => $request->request->get('question_id'),
                'expected' => $this->expected($questionWithResponse, $nextQuestion),
            ];

            // sauvegarde dans la session
            $session->set('reponses', $reponses);
            $session->set('questions', $questionWithResponse);
            $session->set('name', $categorie->getName());
            $session->set('id', $categorie->getId());

            // on check si on est à la fin du quizz, si oui on redirect vers la page de résultat
            if ($nextQuestion > end($questionWithResponse)['question']->getId()) {
                return $this->redirectToRoute('question.resultat');
            }
        }

        // permet de fix un bug.
        // Quand on commence un quizz et que la question n'est pas à un $nextQuestion est égal à
        // 1 au lieu de ne pas avoir de valeur
        // de ce fait si il est égal à 1 on le fix à la première question
        // même si la firstQuestion seras égal à 1 on le fix à 1
        if ($nextQuestion === 1) {
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

    #[Route('/create/quizz', name: 'quizz.create')]
    #[IsGranted('QUIZ_CREATE', subject: 'categorie')]
    public function showCreateCategorieForm(Categorie $categorie): RedirectResponse|Response
    {
        // check si l'utilisateur est vérifier ou tout simplement si il est autorisé à créer un quiz
        $this->denyAccessUnlessGranted('QUIZ_CREATE',
            new Categorie(),
            'Veuillez vérifier votre compte'
        );

        // vérifie que l'utilisateur est bien connecté
        if ($this->getUser() === null) {
            // sinon ont le redirige vers la page de connexion avec un message
            $this->addFlash('info', 'Vous devez être connecté afin de pouvoir créer un quizz.');
            return $this->redirectToRoute('app_login');
        } else {

            // ici on vérifie que l'utilisateur à bien vérifié son compte avant de le rediriger vers la page de création de quizz
            if ($this->getUser()->isVerified()) {
                return $this->render('categorie/create-quizz.html.twig');
            }

            // sinon on le redirige sur la page de son profile afin que il puisse valider son compte
            $this->addFlash('info', "Merci de bien vouloir vérifier votre compte avant de pouvoir créer un quizz.");
            return $this->redirectToRoute('user.profile');
        }
    }

    #[Route('/create/quizz/store', name: 'quizz.create.post', methods: ['POST'])]
    public function storeQuizz(Request $request, EntityManagerInterface $em): RedirectResponse
    {
        // récupère l'utilisateur connecté qui va créer le quizz
        $user = $this->getUser();

        // initialisation du tableau de données
        $data = [];

        // boucle sur les 10 questions afin de remplir le tableau de données
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'question' => $request->request->get('question_' . $i),
                'reponses' => $request->request->all('answers_question_' . $i),
                'reponse_expected' => $request->request->get('correct_answers_question_' . $i),
            ];
        }

        // Création de la catégorie car les relations en auront besoin plus tard (id)
        $quizz = new Categorie();
        $quizz->setName($request->request->get('name'));
        $quizz->setUser($user);

        // check les autorisations avant la persistance en BDD
        $this->denyAccessUnlessGranted('QUIZ_CREATE',
            $quizz,
            'Veuillez vérifier votre compte'
        );

        $em->persist($quizz);
        $em->flush();
        // initialisation des tableaux des données créés en cas d'erreur on les supprime afin de ne pas polluer la BDD
        $createdQuestion = [];
        $createdReponse = [];

        try { // handle error

            // store questions & reponses
            foreach ($data as $value) {
                // Création de la question
                $question = new Question();
                $question->setQuestion(trim($value['question']));
                $question->setIdCategorie($quizz->getId());

                $em->persist($question); // persister les data en BDD
                $em->flush();

                $createdQuestion[] = $question;

                // boucle sur chaque réponse de la question associée
                foreach ($value['reponses'] as $key => $v) {
                    $reponse = new Reponse();
                    $reponse->setReponse(trim($v));

                    /*
                     * Vérifie si la réponse est la bonne réponse, car le champ attendu est de type boolean
                     * */
                    $reponse->setReponseExpected(
                        ((int)$value['reponse_expected'] - 1) === $key
                    );
                    $reponse->setIdQuestion($question->getId());

                    $em->persist($reponse);

                    // store de la réponse en cas d'erreur, on supprime les données enregistrées
                    $createdReponse[] = $reponse;
                }
            }

            $em->flush();
        } catch (\Exception $e) {
            // remove created quizz
            $em->remove($quizz);
            $em->flush();

            // suppression des questions et réponses créées
            foreach ($createdReponse as $r) {
                $em->remove($r);
                $em->flush();
            }

            foreach ($createdQuestion as $q) {
                $em->remove($q);
                $em->flush();
            }

            $this->addFlash('error', "Erreur lors de la création du quizz");
            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('success', 'Quizz créé avec succès');
        return $this->redirectToRoute('quizz', ['id' => $quizz->getId()]);
    }

    #[Route('/quizz/{id}/update', name: 'quizz.update.post')]
    #[IsGranted('ROLE_ADMIN', subject: 'categorie')]
    public function update(Categorie $categorie, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $categorie->setName($request->request->get('name'));
        $em->flush();

        for ($i = 0; $i < 10; $i++) {
            $questionKey = 'question_' . $i + 1;
            $questionId = $questionKey . '_id';

            $_question = $request->request->get($questionKey);
            $questionId = $request->request->get($questionId);
            $reponses = $request->request->all('answers_question_' . $i + 1);
            $correctReponse = $request->request->get('correct_answers_question_' . $i + 1);

            $question = $em->getRepository(Question::class)->find($questionId);
            $question->setQuestion($_question);
            $em->flush();

            $reponse = $em->getRepository(Reponse::class)->findBy(['id_question' => $questionId]);
            for ($j = 0; $j < count($reponse); $j++) {
                $reponse[$j]->setReponse($reponses[$j]);
                $reponse[$j]->setReponseExpected((int)$correctReponse - 1 === $j);
                $em->flush();
            }
        }


        $this->addFlash('success', "Quizz [{$categorie->getName()}] mis à jour avec succès");
        return $this->redirectToRoute('admin.quizz.categorie');
    }

    #[Route('/quizz/{id}/delete', name: 'quizz.delete')]
    #[IsGranted('QUIZ_DELETE', subject: 'categorie')]
    public function delete(EntityManagerInterface $em, Categorie $categorie): RedirectResponse
    {
        // get everything related to user and delete it
        $quizz = $categorie;
        $savedNameOfQuizz = $quizz->getName();

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

        $em->flush();

        $this->addFlash('success', "Le quizz [{$savedNameOfQuizz}] a bien été supprimé.");
        return new RedirectResponse($this->generateUrl('admin.users'));
    }
}
