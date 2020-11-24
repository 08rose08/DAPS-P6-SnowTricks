<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
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
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $rang = max(0, $request->query->getInt('rang', 0));
        $tricks = $trickRepository->findByPaginator($rang);
        $rang++;
        
        $nbPagesMax = ceil(count($trickRepository->findAll()) / TrickRepository::PAGINATOR_PER_PAGE);

        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
            'rang' => $rang,
            'nbPagesMax' => $nbPagesMax,
        ]);
    }
    /**
     * @Route("/more", name="trick_more", methods={"GET"})
     */
    public function loadMore(TrickRepository $trickRepository, Request $request)
    {
        $rang = max(0, $request->query->getInt('rang', 0));
        $tricks = $trickRepository->findByPaginator($rang);
        $rang++;

        //return $this->json(['code' => 200, 'tricks' => $tricks, 'rang' => $rang], 200); 
        //return $this->json(['code' => 200, 'tricks' => $tricks, 'rang' => $rang], 200, [], ['groups' => 'loadMore']); 
        return $this->json(['code' => 200, 'tricks' => "ici les tricks", 'rang' => $rang], 200);
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
     * @Route("trick/{id}", name="trick_show", methods={"GET", "POST"})
     */
    public function show(Comment $comment = null, Trick $trick, Request $request, CommentController $commentController, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {   
        $commentForm = $commentController->new($comment, $trick, $request, $entityManager);
        //$comment = null;
        //$nbComments = count($trick->getComments());

        /*if(empty($numPage)) {$numPage = 1;};
        $nbCommentsPage = 5;
        $nbPages = ceil($nbComments / $nbCommentsPage);
        $comment1 = ($numPage - 1) * $nbCommentsPage;
        $comments = $commentRepository->getCommentsPage($trick, $comment1);
        */
        $offset = max(0, $request->query->getInt('offset', 0));
        //var_dump($offset);
        $paginator = $commentRepository->findByPaginator($trick, $offset);

        $previous = $offset - CommentRepository::PAGINATOR_PER_PAGE;
        //var_dump($previous);
        $next = min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE);
        //var_dump($next);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
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