<?php

namespace App\Controller;

use App\Entity\App;
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

            $this->incrementView($entityManager);
            $user = $this->getUser();
            $categories = $entityManager->getRepository(Categorie::class)->findAll();
            
            if($user && $user->isVerified() == false){
                    $session = ($r->getSession())->get('has_verified_email');
                    if($session == null){
                        $this->addFlash('info', "Veuillez confirmer votre adresse email sur votre page de profile");
                        $r->getSession()->set('has_verified_email', false);
                    }
            } elseif($user) {
                    // update last connection
                    $user->setLastConnection(new \DateTimeImmutable('UTC'));
                    $entityManager->flush();
            }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    private function incrementView(EntityManagerInterface $em):void {
        $app = $em->getRepository(App::class)->find(1);

        if(!$app) {
            $app = new App();
            $app->setView(0);
            $em->persist($app);
            $em->flush();
        }

        $app->setView($app->getView() + 1);
        $em->flush();
    }
}
