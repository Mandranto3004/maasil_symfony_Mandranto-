<?php

namespace App\Controller;


use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Form\AuteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AuteurController extends AbstractController
{

     /**
     * @var AuteurRepository
     */
    private $repository;

    /**
     * @var ManagerRegistry
     */
    private $em;
    
    public function __construct(AuteurRepository $repository, EntityManagerInterface $entityManager){
        $this->repository = $repository;
        $this->em = $entityManager;
    }


    /**
     * @Route("/auteur", name="app_auteur_list")
     */
    public function index(): Response
    {
        $auteurs = $this->repository->findAll();
        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurs
        ]);
    }

    /**
     * @Route("/auteur/{id}", name="app_auteur_edit", methods="GET|POST")
     * @param Auteur $auteur
     * @param Request $request
     */
    public function edit(Auteur $auteur, Request $request){

        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();  
            $this->addFlash('succes', 'Auteur modifié avec succès');
            return $this->redirectToRoute('app_auteur_list');
        }
        return $this->render('auteur/edit.html.twig',[
            'auteur' => $auteur,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/auteur_new", name="app_auteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_auteur_list');
        }

        return $this->render('auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView(),
        ]);
    }
}
