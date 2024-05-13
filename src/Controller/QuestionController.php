<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuestionController extends AbstractController
{
    #[Route('/ques/question', name: 'app_ques_question')]
    public function index(): Response
    {
        return $this->render('ques_question/index.html.twig', [
            'controller_name' => 'QuesQuestionController',
        ]);
    }
}
