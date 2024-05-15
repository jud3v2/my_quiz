<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\UserHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoryController extends AbstractController
{
        #[Route('/history', name: 'history')]
        public function index(Request $r, EntityManagerInterface $em): Response
        {
                if($this->getUser()) {
                        goto a;
                } else {
                        a:
                        $session = $r->getSession();
                        $history = $session->get('history', []);

                        foreach ($history as &$value) {
                                $value['quizz'] = trim($em->getRepository(Categorie::class)
                                    ->find($value['reponses'][0]['categorie'])->getName());
                        }

                        usort($history, function ($a, $b) {
                                return $b['date'] <=> $a['date'];
                        });

                        return $this->render('history/index.html.twig', [
                            'history' => $history,
                        ]);
                }
        }
}
