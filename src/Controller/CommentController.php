<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Controller\TrickController;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Comment $comment = null, Trick $trick, Request $request, EntityManagerInterface $entityManager)//: Response
    {
        if(!$comment) {
            $comment = new Comment();
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \Datetime());
            $trick->addComment($comment);
            $user = $this->getUser();
            $user->addComment($comment);

            $entityManager->persist($comment);
            $entityManager->flush();

            $comment = null;
            $form = $this->createForm(CommentType::class, $comment);
            
            $this->addFlash('info', 'Commentaire ajouté!');
            
        }

        return $form->createView();
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

}