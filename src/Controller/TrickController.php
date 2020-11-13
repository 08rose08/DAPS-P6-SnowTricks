<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\Trick1Type;
use App\Repository\TrickRepository;
use App\Controller\CommentController;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("trick/new", name="trick_new", methods={"GET","POST"})
     * @Route("trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function new(Trick $trick = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$trick) {
            $trick = new Trick();
        }

        $form = $this->createForm(Trick1Type::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$trick->getId()){
                $trick->setCreatedAt(new \Datetime());
                
            }else{
                $trick->setEditAt(new \Datetime());
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->render('trick/new.html.twig', [
            'formTrick' => $form->createView()  
        ]);
    }

    /**
     * @Route("trick/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick, Request $request, CommentController $commentController): Response
    {   
        $commentForm = $commentController->new($request);
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm,
        ]);
    }

    ///**
    // * @Route("trick/{id}/edit", name="trick_edit", methods={"GET","POST"})
    // */
    /*public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(Trick1Type::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'formTrick' => $form->createView(),
        ]);
    }*/

    /**
     * @Route("trick/{id}", name="trick_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index');
    }
}