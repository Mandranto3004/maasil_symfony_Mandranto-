<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var ManagerRegistry
     */
    private $em;
    
    public function __construct(ArticleRepository $repository, EntityManagerInterface $entityManager){
        $this->repository = $repository;
        $this->em = $entityManager;
    }
    /**
     * @Route("/article", name="app_article_list")
     */
    public function index(): Response

    {
        
       
        $articles = $this->repository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }
    
    
    /**
     * @Route("/article/{id}", name="app_article_edit", methods="GET|POST")
     * @param Article $article
     * @param Request $request
     */
    public function edit(Article $article, Request $request){

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();  
            $this->addFlash('succes', 'Article modifié avec succès');
            return $this->redirectToRoute('app_article_list');
        }
        return $this->render('article/edit.html.twig',[
            'article' => $article,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/new", name="app_article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_list');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
