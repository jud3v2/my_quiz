<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    #[Route('/quizz/{id}', name: 'quizz')]
    public function index(EntityManagerInterface $entityManager, Categorie $categorie): Response
    {
            $questions = $entityManager->getRepository(QuestionController::class)
            ->findBy(['id_categorie' => $categorie->getId()]);
        return $this->render('categorie/index.html.twig', [
            'quizz' => $questions,
                'questions' => $categorie->getQuestions(),
        ]);
    }
}
