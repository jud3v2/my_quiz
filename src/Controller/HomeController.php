<?php

namespace App\Controller;

use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager, Request $r): Response
    {

            $user = $this->getUser();
            $categories = $entityManager->getRepository(Categorie::class)->findAll();
            
            if($user->isVerified() == false){
                    $session = ($r->getSession())->get('has_verified_email');
                    if($session == null){
                        $this->addFlash('info', 'Veuillez confirmer votre adresse email');
                        $r->getSession()->set('has_verified_email', false);
                    }
            }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
