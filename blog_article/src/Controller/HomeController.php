<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $repository;

   
    
    public function __construct(ArticleRepository $repository){
        $this->repository = $repository;
    }
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        $articles = $this->repository->Afficher();
        return $this->render('home.html.twig', [
            'articles' => $articles
        ]);
    }    

}
